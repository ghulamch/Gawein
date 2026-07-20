@extends('layouts.dashboard')

@section('title', 'Dasbor Pemberi Kerja')
@section('user_name', Auth::user()->name ?? 'Pemberi Kerja')
@section('user_role', 'Level ' . ($profile->level ?? 1) . ' Recruiter')

@section('sidebar_menu')
    <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link active"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('employer.jobs.create') }}" class="sidebar-link"><i class="fa-solid fa-bullhorn w-5 text-center"></i> Pasang Lowongan</a></li>
    <li><a href="{{ route('employer.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-list-check w-5 text-center"></i> Lowongan Aktif</a></li>
    <li><a href="{{ route('employer.candidates.index') }}" class="sidebar-link"><i class="fa-solid fa-users-viewfinder w-5 text-center"></i> Kandidat Masuk</a></li>
    <li>
        <a href="{{ route('employer.messages.index') }}" class="sidebar-link">
            <i class="fa-solid fa-comment-dots w-5 text-center"></i> 
            <span class="flex-1">Pesan</span>
            @php $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count(); @endphp
            @if($unreadCount > 0)
                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
    </li>
    <li><a href="{{ route('employer.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-building w-5 text-center"></i> Profil Perusahaan</a></li>
    <li><a href="{{ route('employer.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')

@if(session('success'))
<div class="mb-6 bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex justify-between items-center shadow-sm">
    <div class="flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
</div>
@endif


<div class="glass-panel p-6 rounded-2xl mb-8 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute right-20 -bottom-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>

    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 p-1 shadow-xl shadow-blue-500/20 shrink-0">
        <div class="w-full h-full bg-slate-900 rounded-full border-4 border-white flex items-center justify-center relative overflow-hidden">
            <i class="fa-solid fa-building text-3xl text-white z-10"></i>
            <div class="absolute inset-0 bg-blue-500/20 animate-pulse"></div>
        </div>
    </div>
    
    <div class="flex-1 w-full">
        <div class="flex justify-between items-end mb-2">
            <div>
                <h2 class="text-2xl font-heading font-black text-slate-800 tracking-tight">{{ $profile->name ?? 'Perusahaan Anda' }}</h2>
                <div class="text-sm font-semibold text-blue-600 flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-medal text-amber-500"></i> Level {{ $profile->level ?? 1 }} Rekruter
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-black text-slate-800">{{ $profile->xp ?? 0 }} <span class="text-sm text-slate-500 font-medium">XP</span></div>
            </div>
        </div>
        
        
        <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden shadow-inner border border-slate-200/50 relative">
            @php 
                $maxXp = 500 * ($profile->level ?? 1); 
                $percentage = min(100, (($profile->xp ?? 0) / $maxXp) * 100);
            @endphp
            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)] transition-all duration-1000 ease-out relative" style="width: {{ $percentage }}%">
                <div class="absolute inset-0 bg-white/20 animate-[pulse_2s_ease-in-out_infinite]"></div>
            </div>
        </div>
        <div class="text-[11px] font-bold text-slate-400 text-right mt-1">{{ $maxXp - ($profile->xp ?? 0) }} XP lagi menuju Level {{ ($profile->level ?? 1) + 1 }}</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -z-10"></div>
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Lowongan Aktif</div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-briefcase"></i></div>
        </div>
        <div class="text-4xl font-heading font-black text-slate-800 mb-2">{{ $activeJobsCount ?? 0 }}</div>
        <div class="text-xs text-slate-500 font-medium mt-2">Batas maksimum: 5 lowongan gratis</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-50 rounded-bl-full -z-10"></div>
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Pelamar</div>
            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="text-4xl font-heading font-black text-slate-800 mb-2">{{ $totalCandidates ?? 0 }}</div>
        <div class="text-sm font-medium text-emerald-600 flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> +0 lamaran baru minggu ini</div>
    </div>
    
    
    <div class="glass-panel p-6 rounded-2xl card-hover flex flex-col justify-between bg-gradient-to-br from-slate-800 to-slate-900 border-none shadow-xl">
        <div>
            <div class="flex justify-between items-center mb-4">
                <div class="text-sm font-bold text-blue-400 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-star"></i> Misi Harian</div>
                <div class="text-xs font-bold bg-white/10 text-white px-2 py-1 rounded-md border border-white/20">+100 XP</div>
            </div>
            <h3 class="font-heading font-bold text-xl text-white mb-1">Pasang Lowongan Baru</h3>
            <p class="text-sm text-slate-400">Dapatkan pelamar berkualitas hari ini.</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="w-full mt-4 py-3 rounded-xl font-bold text-slate-900 bg-white hover:bg-slate-100 transition-colors flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(255,255,255,0.15)]">
            Mulai Misi <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 glass-panel p-6 rounded-2xl">
        <h3 class="font-heading font-bold text-xl text-slate-800 mb-6">Pencapaian Anda (Badges)</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="border border-emerald-200 bg-emerald-50 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-2 shadow-sm relative overflow-hidden group">
                <div class="absolute inset-0 bg-emerald-100 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-md relative z-10 text-emerald-500 text-xl">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <div class="relative z-10">
                    <div class="font-bold text-sm text-emerald-900">Perintis</div>
                    <div class="text-[10px] text-emerald-700 font-medium">Mendaftar pertama kali</div>
                </div>
            </div>

            <div class="border border-slate-200 bg-slate-50 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-2 relative overflow-hidden grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all cursor-not-allowed">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm relative z-10 text-blue-500 text-xl">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div class="relative z-10">
                    <div class="font-bold text-sm text-slate-700">Rekruter Aktif</div>
                    <div class="text-[10px] text-slate-500 font-medium">Pasang 3 lowongan</div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center bg-slate-900/10 backdrop-blur-[1px] z-20">
                    <i class="fa-solid fa-lock text-slate-600"></i>
                </div>
            </div>

            <div class="border border-slate-200 bg-slate-50 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-2 relative overflow-hidden grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all cursor-not-allowed">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm relative z-10 text-amber-500 text-xl">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <div class="relative z-10">
                    <div class="font-bold text-sm text-slate-700">Deal Maker</div>
                    <div class="text-[10px] text-slate-500 font-medium">Terima 5 pelamar</div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center bg-slate-900/10 backdrop-blur-[1px] z-20">
                    <i class="fa-solid fa-lock text-slate-600"></i>
                </div>
            </div>

        </div>
    </div>
    
    
    <div class="glass-panel p-6 rounded-2xl flex flex-col justify-center bg-slate-50 border border-slate-200/60 shadow-inner">
        <h3 class="font-heading font-bold text-xl text-slate-800 mb-6 text-center">Aksi Cepat</h3>
        
        <a href="{{ route('employer.jobs.create') }}" class="w-full mb-4 py-4 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Pasang Lowongan
        </a>
        <a href="{{ route('employer.candidates.index') }}" class="w-full py-4 rounded-xl font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="fa-solid fa-search text-slate-400"></i> Cari Kandidat
        </a>
    </div>
</div>
@endsection
