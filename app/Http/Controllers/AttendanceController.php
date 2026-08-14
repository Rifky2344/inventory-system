<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role->name === 'admin' || $user->role->name === 'super-admin') {
            $attendances = Attendance::with('user.role')->orderBy('check_in_time', 'desc')->get();
        } else {
            $attendances = Attendance::with('user.role')->where('user_id', $user->id)->orderBy('check_in_time', 'desc')->get();
        }

        return view('attendances.index', compact('attendances'));
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

        Attendance::create([
            'user_id' => Auth::id(),
            'check_in_time' => now(),
            'photo_path' => $path,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Berhasil melakukan absen.');
    }
}
