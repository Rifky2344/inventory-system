<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\StockMutation;
use App\Models\StockTransaction;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->with('role')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhereHas('role', function ($query) use ($q) {
                            $query
                                ->where('name', 'like', "%{$q}%")
                                ->orWhere('slug', 'like', "%{$q}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(request('per_page', 20))
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_create',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user->load('role'),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (!$request->filled('password')) {
            unset($data['password']);
        }

        $superAdminRoleId = Role::query()->where('slug', 'super-admin')->value('id');
        $superAdminCount = User::query()->where('role_id', $superAdminRoleId)->count();

        $isTargetSuperAdmin = (string) $user->role_id === (string) $superAdminRoleId;
        $isDemotingLastSuperAdmin = $isTargetSuperAdmin
            && $superAdminCount <= 1
            && (string) $data['role_id'] !== (string) $superAdminRoleId;

        if ($isDemotingLastSuperAdmin) {
            return back()->withInput()->withErrors([
                'role_id' => 'Tidak boleh mengubah role super-admin terakhir.',
            ]);
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_update',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ((string) $request->user()->id === (string) $user->id) {
            return back()->withErrors(['user' => 'Tidak bisa menghapus akun sendiri.']);
        }

        $superAdminRoleId = Role::query()->where('slug', 'super-admin')->value('id');
        $superAdminCount = User::query()->where('role_id', $superAdminRoleId)->count();

        $isTargetSuperAdmin = (string) $user->role_id === (string) $superAdminRoleId;
        if ($isTargetSuperAdmin && $superAdminCount <= 1) {
            return back()->withErrors(['user' => 'Tidak boleh menghapus super-admin terakhir.']);
        }

        $hasTransactions = StockTransaction::query()->where('created_by', $user->id)->exists();
        $hasMutations = StockMutation::query()->where('user_id', $user->id)->exists();
        $hasActivity = ActivityLog::query()->where('user_id', $user->id)->exists();

        if ($hasTransactions || $hasMutations || $hasActivity) {
            return back()->withErrors([
                'user' => 'User tidak bisa dihapus karena sudah memiliki riwayat transaksi/mutasi/activity log. Pertimbangkan untuk mengganti role atau reset password.',
            ]);
        }

        $userId = $user->id;
        $user->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_delete',
            'subject_type' => User::class,
            'subject_id' => $userId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
