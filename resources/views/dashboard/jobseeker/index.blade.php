@extends('layouts.dashboard')

@section('title', 'Dasbor Pencari Kerja')
@section('user_name', Auth::user()->name ?? 'Pencari Kerja')
@section('user_role', 'Kandidat Profesional')

@section('sidebar_menu')
    <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link active"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
    <li><a href="{{ route('jobseeker.applications.index') }}" class="sidebar-link"><i class="fa-solid fa-paper-plane w-5 text-center"></i> Lamaran Saya</a></li>
    <li><a href="{{ route('jobseeker.saved.index') }}" class="sidebar-link"><i class="fa-solid fa-bookmark w-5 text-center"></i> Tersimpan</a></li>
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


<div class="glass-panel p-6 rounded-2xl mb-8 relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute right-20 -bottom-10 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-heading font-black mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-blue-100 font-medium max-w-lg">Siap untuk menemukan pekerjaan impianmu hari ini? Ada 24 lowongan baru yang cocok dengan profilmu.</p>
        </div>
        
        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-xl flex items-center gap-4 shrink-0">
            <div class="w-14 h-14 bg-white text-blue-600 rounded-full flex items-center justify-center text-2xl font-black shadow-lg">
                <i class="fa-solid fa-rocket"></i>
            </div>
            <div>
                <div class="text-sm text-blue-100 font-bold uppercase tracking-wide">Kelengkapan Profil</div>
                <div class="flex items-center gap-2 mt-1">
                    <div class="w-32 h-2 bg-black/20 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-400 rounded-full w-[{{ $profile->profile_completion ?? 10 }}%]"></div>
                    </div>
                    <div class="text-sm font-black">{{ $profile->profile_completion ?? 10 }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl card-hover relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -z-10 transition-transform group-hover:scale-150 duration-500"></div>
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Lamaran</div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg"><i class="fa-solid fa-paper-plane"></i></div>
        </div>
        <div class="text-4xl font-heading font-black text-slate-800 mb-2">{{ $totalApplications }}</div>
        <div class="text-xs font-bold text-blue-600">Menunggu proses seleksi</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-16 h-16 bg-amber-50 rounded-bl-full -z-10 transition-transform group-hover:scale-150 duration-500"></div>
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">Disimpan</div>
            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg"><i class="fa-solid fa-bookmark"></i></div>
        </div>
        <div class="text-4xl font-heading font-black text-slate-800 mb-2">{{ $savedJobsCount }}</div>
        <div class="text-xs font-bold text-slate-500">Pekerjaan favorit</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-bl-full -z-10 transition-transform group-hover:scale-150 duration-500"></div>
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">Panggilan</div>
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg"><i class="fa-solid fa-phone"></i></div>
        </div>
        <div class="text-4xl font-heading font-black text-slate-800 mb-2">{{ $interviewCount }}</div>
        <div class="text-xs font-bold text-emerald-600">Jadwal Interview</div>
    </div>
</div>


<div class="mb-6 flex justify-between items-end">
    <div>
        <h3 class="font-heading font-bold text-xl text-slate-800">Rekomendasi Untukmu</h3>
        <p class="text-sm text-slate-500">Berdasarkan profil dan keahlianmu</p>
    </div>
    <a href="{{ route('jobseeker.jobs.index') }}" class="text-blue-600 font-bold text-sm hover:underline">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
</div>

@if($recommendedJobs->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($recommendedJobs as $job)
        <div class="glass-panel p-6 rounded-2xl hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 border border-slate-200/60 group flex flex-col h-full relative overflow-hidden bg-white">
            
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                    @if($job->employer?->companyProfile?->logo)
                        <img src="{{ asset('uploads/' . $job->employer->companyProfile->logo) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-building text-slate-400 text-xl"></i>
                    @endif
                </div>
                <div class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded-md border border-emerald-200">
                    Sesuai Profil
                </div>
            </div>

            <div class="flex-1">
                <h3 class="font-heading font-bold text-lg text-slate-800 mb-1 group-hover:text-blue-600 transition-colors line-clamp-1">{{ $job->title }}</h3>
                <div class="text-sm font-medium text-slate-500 mb-3 line-clamp-1">{{ $job->employer?->companyProfile?->name ?? $job->employer?->name ?? 'Perusahaan Anonim' }}</div>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-md border border-slate-200"><i class="fa-solid fa-location-dot mr-1"></i> {{ explode(',', $job->location)[0] }}</span>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-md border border-slate-200">{{ $job->type }}</span>
                </div>
                
                <div class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-slate-400"></i> {{ $job->salary }}
                </div>
            </div>
            
            <form action="{{ route('jobseeker.jobs.apply', $job->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl font-bold text-slate-700 bg-slate-50 border border-slate-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all flex items-center justify-center gap-2">
                    Lamar Cepat <i class="fa-solid fa-bolt"></i>
                </button>
            </form>
        </div>
        @endforeach
    </div>
@else
<div class="glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center border-dashed border-2 border-slate-200">
    <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-3xl mb-4">
        <i class="fa-solid fa-briefcase"></i>
    </div>
    <div class="font-bold text-slate-700 text-lg">Belum ada rekomendasi</div>
    <div class="text-sm text-slate-500 mb-6">Lengkapi profilmu agar kami dapat merekomendasikan pekerjaan yang pas.</div>
    <a href="{{ route('jobseeker.profile.index') }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold shadow-sm hover:bg-blue-700 transition-colors">
        Lengkapi Profil
    </a>
</div>
@endif

@endsection
