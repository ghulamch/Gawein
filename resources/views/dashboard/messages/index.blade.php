@extends('layouts.dashboard')

@section('title', 'Pesan')
@section('user_name', Auth::user()->name)
@section('user_role', Auth::user()->role == 'employer' ? 'Recruiter' : 'Kandidat Profesional')

@section('sidebar_menu')
    @if(Auth::user()->role == 'employer')
        <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
        <li><a href="{{ route('employer.jobs.create') }}" class="sidebar-link"><i class="fa-solid fa-bullhorn w-5 text-center"></i> Pasang Lowongan</a></li>
        <li><a href="{{ route('employer.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-list-check w-5 text-center"></i> Lowongan Aktif</a></li>
        <li><a href="{{ route('employer.candidates.index') }}" class="sidebar-link"><i class="fa-solid fa-users-viewfinder w-5 text-center"></i> Kandidat Masuk</a></li>
        <li>
        <a href="{{ route('employer.messages.index') }}" class="sidebar-link active">
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
    @else
        <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
        <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
        <li><a href="{{ route('jobseeker.applications.index') }}" class="sidebar-link"><i class="fa-solid fa-paper-plane w-5 text-center"></i> Lamaran Saya</a></li>
        <li><a href="{{ route('jobseeker.saved.index') }}" class="sidebar-link"><i class="fa-solid fa-bookmark w-5 text-center"></i> Tersimpan</a></li>
        <li>
        <a href="{{ route('jobseeker.messages.index') }}" class="sidebar-link active">
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
    @endif
@endsection

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden flex flex-col h-[calc(100vh-160px)]">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white/50">
        <div>
            <h3 class="font-heading font-black text-xl text-slate-800">Inbox Pesan</h3>
            <p class="text-sm text-slate-500">Pesan dari {{ Auth::user()->role == 'employer' ? 'pelamar' : 'pemberi kerja' }}</p>
        </div>
    </div>
    
    <div class="flex-1 overflow-y-auto p-4 space-y-2">
        @forelse($conversations as $conv)
            @php 
                $routePrefix = Auth::user()->role == 'employer' ? 'employer.' : 'jobseeker.';
                $chatUrl = route($routePrefix . 'messages.show', $conv['user']->id);

                if (Auth::user()->role == 'employer') {
                    $avatar = $conv['user']->seekerProfile?->avatar ? asset('uploads/'.$conv['user']->seekerProfile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($conv['user']->name).'&background=f1f5f9';
                } else {
                    $avatar = $conv['user']->employerProfile?->logo ? asset('uploads/'.$conv['user']->employerProfile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($conv['user']->name).'&background=f1f5f9'; // Assuming relation employerProfile or similar exists. Let's just use ui-avatars.
                }
                $avatar = 'https://ui-avatars.com/api/?name='.urlencode($conv['user']->name).'&background=f1f5f9'; // Simple fallback
            @endphp
            <a href="{{ $chatUrl }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all cursor-pointer group">
                <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden shrink-0 relative">
                    <img src="{{ $avatar }}" class="w-full h-full object-cover">
                    @if($conv['unread_count'] > 0)
                        <span class="absolute top-0 right-0 w-3 h-3 bg-rose-500 border-2 border-white rounded-full"></span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-1">
                        <h4 class="font-bold text-slate-800 text-sm truncate group-hover:text-blue-600 transition-colors">{{ $conv['user']->name }}</h4>
                        <span class="text-[10px] font-bold text-slate-400">{{ $conv['latest_message']->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-xs text-slate-500 truncate flex justify-between items-center">
                        <span class="{{ $conv['unread_count'] > 0 ? 'font-bold text-slate-800' : '' }}">
                            @if($conv['latest_message']->sender_id == Auth::id())
                                <span class="text-slate-400"><i class="fa-solid fa-check"></i></span>
                            @endif
                            {{ $conv['latest_message']->content }}
                        </span>
                        @if($conv['unread_count'] > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $conv['unread_count'] }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-center p-8">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4">
                    <i class="fa-regular fa-comments"></i>
                </div>
                <h4 class="font-bold text-slate-700 text-lg">Belum Ada Percakapan</h4>
                <p class="text-slate-500 text-sm max-w-sm mt-2">
                    @if(Auth::user()->role == 'employer')
                        Mulai percakapan dengan kandidat melalui halaman Kandidat Masuk.
                    @else
                        Percakapan akan muncul di sini jika Pemberi Kerja menghubungi Anda.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection
