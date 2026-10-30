<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $summary = null;

        if (in_array($user->role?->slug, ['admin', 'super-admin'])) {
            $attendances = Attendance::with('user.role')->orderBy('check_in_time', 'desc')->get();

            // Rekap hari berjalan
            $today = now()->toDateString();

            // User yang sudah absen hari ini
            $attendedTodayIds = Attendance::whereDate('check_in_time', $today)
                ->distinct('user_id')
                ->pluck('user_id');

            $attendedToday = User::with('role')
                ->whereIn('id', $attendedTodayIds)
                ->get();

            // Semua user aktif yang belum absen hari ini
            $notAttendedToday = User::with('role')
                ->whereNotIn('id', $attendedTodayIds)
                ->get();

            $summary = [
                'attended_today' => $attendedToday->count(),
                'attended_users' => $attendedToday,
                'not_attended_users' => $notAttendedToday,
            ];
        } else {
            $attendances = Attendance::with('user.role')->where('user_id', $user->id)->orderBy('check_in_time', 'desc')->get();
        }

        return view('attendances.index', compact('attendances', 'summary'));
    }

    public function create()
    {
        return view('attendances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // max 2MB
        ]);

        $path = $request->file('photo')->store('attendances', 'public');

        // Save exactly as provided by the client, without timezone conversion
        $checkInTime = $request->filled('local_time')
            ? $request->local_time
            : now();

        Attendance::create([
            'user_id' => Auth::id(),
            'check_in_time' => $checkInTime,
            'photo_path' => $path,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Berhasil melakukan absen.');
    }

    public function show(Attendance $attendance)
    {
        $user = Auth::user();

        if (!in_array($user->role?->slug, ['admin', 'super-admin']) && $attendance->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail absensi ini.');
        }

        return view('attendances.show', compact('attendance'));
    }
}
