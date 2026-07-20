@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna')
@section('user_name', 'Administrator')
@section('user_role', 'Super Admin')

@section('sidebar_menu')
    <li><a href="{{ route('admin') }}" class="sidebar-link {{ request()->routeIs('admin') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie w-5 text-center"></i> Ringkasan</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="fa-solid fa-users w-5 text-center"></i> Kelola Pengguna</a></li>
    <li><a href="{{ route('admin.security') }}" class="sidebar-link {{ request()->routeIs('admin.security') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved w-5 text-center"></i> Verifikasi Keamanan</a></li>
@endsection

@section('content')
    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h3 class="font-heading font-bold text-xl text-slate-800">Daftar Seluruh Pengguna</h3>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white btn-action flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Pengguna
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Nama & Email</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Peran (Role)</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Tanggal Bergabung</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4">
                                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                <div class="text-sm text-slate-500">{{ $user->email }}</div>
                            </td>
                            <td class="py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                        <i class="fa-solid fa-crown"></i> Admin
                                    </span>
                                @elseif($user->role === 'employer')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <i class="fa-solid fa-building"></i> Pemberi Kerja
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <i class="fa-solid fa-user-tie"></i> Pencari Kerja
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 text-slate-600 font-medium">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center" title="Edit Pengguna">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }} secara permanen?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-colors flex items-center justify-center" title="Hapus Pengguna">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500 font-medium">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        
        <div class="mt-8 flex justify-center w-full">
            {{ $users->links() }}
        </div>
    </div>
@endsection
