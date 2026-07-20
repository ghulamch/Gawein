<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::latest()->paginate(10);
        return view('dashboard.users', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,employer,jobseeker',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('dashboard.users_form', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,employer,jobseeker',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        
        if ($user->id === auth()->id() || $user->email === 'admin@gawein.com') {
            return back()->with('error', 'Tidak dapat menghapus akun admin utama atau akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus dari sistem.');
    }
}
