<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            } elseif ($user->role === 'employer') {
                return redirect()->intended('/pemberi-kerja');
            } else {
                return redirect()->intended('/pencari-kerja');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:jobseeker,employer'],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'employer') {
            \App\Models\CompanyProfile::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
        } elseif ($user->role === 'jobseeker') {
            \App\Models\SeekerProfile::create([
                'user_id' => $user->id,
            ]);
        }

        Auth::login($user);

        if ($user->role === 'employer') {
            return redirect()->intended('/pemberi-kerja');
        } else {
            return redirect()->intended('/pencari-kerja');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(1000, 9999);
        
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $otp, 'created_at' => now()]
        );

        return redirect()->route('otp')->with([
            'email' => $request->email,
            'otp' => $otp,
            'success' => 'Kode OTP berhasil dibuat (Lokal).'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|array',
            'otp.*' => 'required|numeric'
        ]);

        $otpString = implode('', $request->otp);

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $otpString)
            ->first();

        if (!$record) {
            return back()->with('error', 'Kode OTP salah atau tidak valid.');
        }

        return redirect()->route('reset-password')->with([
            'email' => $request->email,
            'token' => $otpString
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();

            \Illuminate\Support\Facades\DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah. Silakan masuk.');
        }

        return back()->with('error', 'Terjadi kesalahan.');
    }
}
