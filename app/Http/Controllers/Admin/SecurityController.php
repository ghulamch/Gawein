<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SecurityController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('verification_status', 'pending')->latest()->paginate(10);
        $verifiedCount = User::where('verification_status', 'verified')->count();
        $rejectedCount = User::where('verification_status', 'rejected')->count();
        $pendingCount = User::where('verification_status', 'pending')->count();
        
        return view('dashboard.security', compact('pendingUsers', 'verifiedCount', 'rejectedCount', 'pendingCount'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $user = User::findOrFail($id);
        
        if ($request->action === 'approve') {
            $user->verification_status = 'verified';
            $message = 'Identitas pengguna berhasil diverifikasi.';
        } else {
            $user->verification_status = 'rejected';
            
            
            
            
            $message = 'Identitas pengguna ditolak.';
        }
        
        $user->save();

        return back()->with('success', $message);
    }
}
