@extends('layouts.dashboard')

@section('title', 'Kelola Lowongan')
@section('user_name', 'Administrator')
@section('user_role', 'Super Admin')

@section('sidebar_menu')
    <li><a href="{{ route('admin') }}" class="sidebar-link {{ request()->routeIs('admin') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie w-5 text-center"></i> Ringkasan</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="fa-solid fa-users w-5 text-center"></i> Kelola Pengguna</a></li>
    <li><a href="{{ route('admin.jobs') }}" class="sidebar-link {{ request()->routeIs('admin.jobs') ? 'active' : '' }}"><i class="fa-solid fa-briefcase w-5 text-center"></i> Kelola Lowongan</a></li>
    <li><a href="{{ route('admin.transactions') }}" class="sidebar-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Laporan Transaksi</a></li>
    <li><a href="{{ route('admin.security') }}" class="sidebar-link {{ request()->routeIs('admin.security') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved w-5 text-center"></i> Verifikasi Keamanan</a></li>
    <li><a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan Sistem</a></li>
@endsection

@section('content')
    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h3 class="font-heading font-bold text-xl text-slate-800">Daftar Lowongan Pekerjaan</h3>
            <button class="bg-blue-600 hover:bg-blue-700 text-white btn-action flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Lowongan
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Posisi Pekerjaan</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Tipe / Lokasi</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 text-sm font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500">
                            <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3 block"></i>
                            <span class="font-medium">Data lowongan belum tersedia (Tahap Pengembangan).</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
