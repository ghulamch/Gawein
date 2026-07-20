@extends('layouts.dashboard')

@section('title', 'Dashboard Pencari Kerja')
@section('user_name', 'Siti Aminah')
@section('user_role', 'Pencari Kerja')

@section('sidebar_menu')
    <li><a href="#" class="sidebar-link active"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="#" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
    <li><a href="#" class="sidebar-link"><i class="fa-solid fa-file-lines w-5 text-center"></i> Lamaran Saya</a></li>
    <li><a href="#" class="sidebar-link"><i class="fa-solid fa-bookmark w-5 text-center"></i> Tersimpan</a></li>
    <li><a href="#" class="sidebar-link"><i class="fa-solid fa-user-pen w-5 text-center"></i> Profil Saya</a></li>
    <li><a href="#" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Lamaran Terkirim</div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-paper-plane"></i></div>
        </div>
        <div class="text-4xl font-heading font-bold text-slate-800">12</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Panggilan Wawancara</div>
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-phone-volume"></i></div>
        </div>
        <div class="text-4xl font-heading font-bold text-slate-800">2</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Dilihat Perusahaan</div>
            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-eye"></i></div>
        </div>
        <div class="text-4xl font-heading font-bold text-slate-800">45</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-heading font-bold text-xl text-slate-800">Rekomendasi (Sekitar Anda)</h3>
            <a href="#" class="text-blue-600 text-sm font-semibold hover:text-blue-800 transition-colors">Eksplorasi</a>
        </div>
        
        <div class="flex flex-col gap-4">
            
            <div class="p-5 rounded-xl border border-slate-200 hover:border-blue-400 transition-colors cursor-pointer group bg-white shadow-sm hover:shadow-md">
                <div class="flex justify-between items-start mb-2">
                    <div class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors">Tenaga Bersih-Bersih Lepas</div>
                    <div class="text-blue-600 font-bold whitespace-nowrap bg-blue-50 px-2 py-1 rounded text-sm">Rp 150k / hari</div>
                </div>
                <div class="text-sm text-slate-500 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot"></i> 2.5 km dari lokasi Anda &bull; PT. Bersih Selalu
                </div>
                <div class="flex gap-2 flex-wrap">
                    <span class="px-2 py-1 rounded text-xs font-semibold bg-sky-100 text-sky-700">Harian</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold bg-emerald-100 text-emerald-700">Tanpa Syarat Ijazah</span>
                </div>
            </div>
            
            
            <div class="p-5 rounded-xl border border-slate-200 hover:border-blue-400 transition-colors cursor-pointer group bg-white shadow-sm hover:shadow-md">
                <div class="flex justify-between items-start mb-2">
                    <div class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors">Penjaga Stand Makanan</div>
                    <div class="text-blue-600 font-bold whitespace-nowrap bg-blue-50 px-2 py-1 rounded text-sm">Rp 2.5M / bulan</div>
                </div>
                <div class="text-sm text-slate-500 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot"></i> 4.1 km dari lokasi Anda &bull; Warung Pak Budi
                </div>
                <div class="flex gap-2 flex-wrap">
                    <span class="px-2 py-1 rounded text-xs font-semibold bg-amber-100 text-amber-700">Shift Malam</span>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="glass-panel p-6 rounded-2xl flex flex-col">
        <h3 class="font-heading font-bold text-xl text-slate-800 mb-6">Kelengkapan Profil</h3>
        
        <div class="mb-2 flex justify-between text-sm font-semibold">
            <span class="text-slate-700">Progres</span>
            <span class="text-blue-600">75%</span>
        </div>
        <div class="w-full h-2 bg-slate-200 rounded-full mb-6 overflow-hidden">
            <div class="w-3/4 h-full bg-blue-600 shadow-[0_0_10px_rgba(37,99,235,0.5)] rounded-full"></div>
        </div>
        
        <p class="text-sm text-slate-600 leading-relaxed mb-6 flex-1">Lengkapi profil Anda untuk meningkatkan peluang dipanggil wawancara hingga 80%.</p>
        
        <a href="#" class="w-full py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all text-center mb-8">
            Lengkapi Sekarang
        </a>
        
        <div>
            <h4 class="font-bold text-slate-800 text-sm mb-3">Belum Dilengkapi:</h4>
            <ul class="text-sm text-slate-600 space-y-2">
                <li class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark text-rose-500"></i> Foto KTP</li>
                <li class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark text-rose-500"></i> Riwayat Pekerjaan Sebelumnya</li>
            </ul>
        </div>
    </div>
</div>
@endsection
