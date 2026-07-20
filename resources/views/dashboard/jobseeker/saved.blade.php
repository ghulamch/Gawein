@extends('layouts.dashboard')

@section('title', 'Lowongan Tersimpan')
@section('user_name', Auth::user()->name ?? 'Pencari Kerja')
@section('user_role', 'Kandidat Profesional')

@section('sidebar_menu')
    <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
    <li><a href="{{ route('jobseeker.applications.index') }}" class="sidebar-link"><i class="fa-solid fa-paper-plane w-5 text-center"></i> Lamaran Saya</a></li>
    <li><a href="{{ route('jobseeker.saved.index') }}" class="sidebar-link active"><i class="fa-solid fa-bookmark w-5 text-center"></i> Tersimpan</a></li>
    <li>
        <a href="{{ route('jobseeker.messages.index') }}" class="sidebar-link">
            <i class="fa-solid fa-comment-dots w-5 text-center"></i> 
            <span class="flex-1">Pesan</span>
            @php $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count(); @endphp
            @if($unreadCount > 0)
                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
    </li>
    <li><a href="{{ route('jobseeker.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-user w-5 text-center"></i> Profil Saya</a></li>
    <li><a href="{{ route('jobseeker.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')
<div class="mb-4 flex items-center justify-between">
    <h3 class="font-heading font-bold text-xl text-slate-800">Lowongan Disimpan ({{ $savedJobs->count() }})</h3>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($savedJobs as $saved)
    <div class="glass-panel p-6 rounded-2xl card-hover flex flex-col group relative overflow-hidden">
        <button class="absolute top-4 right-4 w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-slate-100 hover:text-slate-400 transition-colors z-10" title="Hapus dari tersimpan">
            <i class="fa-solid fa-bookmark"></i>
        </button>
        
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl bg-white border border-slate-100 shadow-sm p-1 overflow-hidden shrink-0">
                <img src="{{ $saved->job->employer?->companyProfile?->logo ? asset('uploads/' . $saved->job->employer->companyProfile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($saved->job->employer?->companyProfile?->name ?? 'Company').'&background=f1f5f9' }}" class="w-full h-full object-cover rounded-lg">
            </div>
            <div>
                <h3 class="font-heading font-black text-slate-800 text-lg group-hover:text-blue-600 transition-colors leading-tight">{{ $saved->job->title }}</h3>
                <div class="text-sm font-semibold text-slate-500 mt-1">{{ $saved->job->employer?->companyProfile?->name ?? 'Perusahaan' }}</div>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-blue-50 text-blue-600 border border-blue-100 flex items-center gap-1">
                <i class="fa-solid fa-briefcase"></i> {{ $saved->job->type }}
            </span>
            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1">
                <i class="fa-solid fa-money-bill-wave"></i> {{ $saved->job->salary }}
            </span>
        </div>
        
        <p class="text-sm text-slate-600 line-clamp-2 mb-6 flex-1">{{ $saved->job->description }}</p>
        
        <div class="mt-auto pt-4 border-t border-slate-100 flex justify-between items-center">
            <div class="text-[11px] font-bold text-slate-400">Disimpan {{ $saved->created_at->diffForHumans() }}</div>
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-colors shadow-sm">
                Lamar
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-full glass-panel p-12 rounded-2xl flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4">
            <i class="fa-regular fa-bookmark"></i>
        </div>
        <h2 class="text-2xl font-heading font-black text-slate-800 mb-2">Tidak Ada Lowongan Tersimpan</h2>
        <p class="text-slate-500 max-w-md mx-auto">Klik ikon bookmark pada lowongan yang Anda sukai untuk menyimpannya di sini.</p>
    </div>
    @endforelse
</div>
@endsection
