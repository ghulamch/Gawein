@extends('layouts.dashboard')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna Baru')
@section('user_name', 'Administrator')
@section('user_role', 'Super Admin')

@section('sidebar_menu')
    <li><a href="{{ route('admin') }}" class="sidebar-link"><i class="fa-solid fa-chart-pie w-5 text-center"></i> Ringkasan</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link active"><i class="fa-solid fa-users w-5 text-center"></i> Kelola Pengguna</a></li>
    <li><a href="{{ route('admin.security') }}" class="sidebar-link"><i class="fa-solid fa-shield-halved w-5 text-center"></i> Verifikasi Keamanan</a></li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto mb-10">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 flex items-center justify-center transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h3 class="font-heading font-bold text-2xl text-slate-800">{{ isset($user) ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}</h3>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="glass-panel p-8 rounded-[2rem] shadow-sm relative overflow-hidden">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-10 opacity-50"></div>

        <div class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <div class="relative">
                    <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold" placeholder="Masukkan nama pengguna">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold" placeholder="email@contoh.com">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Peran (Role)</label>
                <div class="relative">
                    <i class="fa-solid fa-user-shield absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select name="role" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold appearance-none cursor-pointer">
                        <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Pilih Peran Pengguna...</option>
                        <option value="jobseeker" {{ old('role', $user->role ?? '') == 'jobseeker' ? 'selected' : '' }}>Pencari Kerja</option>
                        <option value="employer" {{ old('role', $user->role ?? '') == 'employer' ? 'selected' : '' }}>Pemberi Kerja</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ isset($user) ? 'Kata Sandi Baru (Kosongkan jika tidak ingin diubah)' : 'Kata Sandi' }}</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" name="password" {{ isset($user) ? '' : 'required' }} class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold" placeholder="Minimal 8 karakter">
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.users') }}" class="px-6 py-3 rounded-xl font-bold text-sm bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-xl font-bold text-sm bg-blue-600 text-white hover:bg-blue-700 shadow-md shadow-blue-500/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <i class="fa-solid fa-save"></i> {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
            </button>
        </div>
    </form>
</div>
@endsection
