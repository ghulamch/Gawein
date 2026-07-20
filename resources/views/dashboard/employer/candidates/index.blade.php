@extends('layouts.dashboard')

@section('title', 'Kandidat Masuk')
@section('user_name', Auth::user()->name ?? 'Pemberi Kerja')
@section('user_role', 'Recruiter')

@section('sidebar_menu')
    <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('employer.jobs.create') }}" class="sidebar-link"><i class="fa-solid fa-bullhorn w-5 text-center"></i> Pasang Lowongan</a></li>
    <li><a href="{{ route('employer.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-list-check w-5 text-center"></i> Lowongan Aktif</a></li>
    <li><a href="{{ route('employer.candidates.index') }}" class="sidebar-link active"><i class="fa-solid fa-users-viewfinder w-5 text-center"></i> Kandidat Masuk</a></li>
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
<div class="glass-panel p-6 rounded-2xl flex flex-col mb-8">
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-heading font-bold text-xl text-slate-800">Daftar Pelamar</h3>
        
        <form action="{{ route('employer.candidates.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 w-64" placeholder="Cari nama pelamar...">
            </div>
            <div class="relative hidden sm:block">
                <i class="fa-solid fa-location-dot absolute left-3 top-2.5 text-slate-400"></i>
                <input type="text" name="location" value="{{ request('location') }}" class="pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 w-48" placeholder="Domisili kandidat...">
            </div>
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-semibold border border-slate-200 hover:bg-slate-200 outline-none w-full sm:w-auto">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            
            <button type="submit" class="hidden">Filter</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50">
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider rounded-tl-lg">Kandidat</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Melamar Posisi</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kecocokan</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($applications as $application)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden">
                                <img src="{{ $application->user->seekerProfile?->avatar ? asset('uploads/' . $application->user->seekerProfile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($application->user->name).'&background=f1f5f9' }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $application->user->name }}</div>
                                <div class="text-[11px] font-semibold text-slate-500"><i class="fa-regular fa-envelope"></i> {{ $application->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm font-semibold text-slate-700">{{ $application->job->title }}</div>
                        <div class="text-[11px] text-slate-500">Dilamar {{ $application->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-slate-200 rounded-full h-1.5 max-w-[80px]">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $application->user->seekerProfile?->profile_completion ?? 10 }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-emerald-600">{{ $application->user->seekerProfile?->profile_completion ?? 10 }}%</span>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <form action="{{ route('employer.candidates.updateStatus', $application->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border outline-none
                                {{ $application->status == 'pending' ? 'bg-slate-50 text-slate-600 border-slate-200' : '' }}
                                {{ $application->status == 'review' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                                {{ $application->status == 'interview' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                {{ $application->status == 'accepted' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                {{ $application->status == 'rejected' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}
                            ">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="review" {{ $application->status == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </form>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex justify-end gap-2">
                            @php
                                $unreadFromCandidate = \App\Models\Message::where('sender_id', $application->user->id)
                                    ->where('receiver_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('employer.messages.show', $application->user->id) }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded hover:bg-blue-700 shadow-sm transition-all flex items-center gap-1 relative">
                                <i class="fa-solid fa-comment-dots"></i> Chat
                                @if($unreadFromCandidate > 0)
                                    <span class="absolute -top-2 -right-2 bg-rose-500 text-white text-[9px] font-black w-4 h-4 flex items-center justify-center rounded-full border-2 border-white">{{ $unreadFromCandidate }}</span>
                                @endif
                            </a>
                            @if($application->user->seekerProfile?->resume)
                            <a href="{{ asset('uploads/' . $application->user->seekerProfile->resume) }}" target="_blank" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded hover:bg-slate-50 shadow-sm transition-all flex items-center gap-1">
                                <i class="fa-solid fa-file-pdf"></i> CV
                            </a>
                            @else
                            <span class="text-[10px] text-slate-400 font-medium px-2 py-1">No CV</span>
                            @endif

                            @if($application->status === 'accepted' && $application->transaction_id)
                                @php
                                    $hasReviewed = \App\Models\Review::where('transaction_id', $application->transaction_id)->where('reviewer_id', Auth::id())->exists();
                                @endphp
                                @if(!$hasReviewed)
                                    <button class="btn-review px-3 py-1.5 bg-amber-500 text-white text-xs font-bold rounded hover:bg-amber-600 shadow-sm transition-all flex items-center gap-1"
                                        data-transaction="{{ $application->transaction_id }}"
                                        data-reviewee="{{ $application->user->id }}"
                                    ><i class="fa-solid fa-star"></i> Ulas</button>
                                @else
                                    <span class="px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-bold rounded cursor-not-allowed flex items-center gap-1"><i class="fa-solid fa-check"></i> Diulas</span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">
                            <i class="fa-solid fa-users-slash"></i>
                        </div>
                        <div class="font-bold text-slate-600">Belum Ada Pelamar</div>
                        <div class="text-sm text-slate-500 mt-1">Saat ini belum ada kandidat yang melamar lowongan Anda.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-review').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction');
            const revieweeId = this.getAttribute('data-reviewee');
            
            Swal.fire({
                title: 'Berikan Ulasan Pekerja',
                html: `
                    <form id="reviewForm" method="POST" action="/transactions/${transactionId}/reviews">
                        @csrf
                        <input type="hidden" name="reviewee_id" value="${revieweeId}">
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rating Kinerja (1-5)</label>
                            <select name="rating" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none" required>
                                <option value="5">⭐⭐⭐⭐⭐ Sangat Baik (5)</option>
                                <option value="4">⭐⭐⭐⭐ Baik (4)</option>
                                <option value="3">⭐⭐⭐ Cukup (3)</option>
                                <option value="2">⭐⭐ Kurang (2)</option>
                                <option value="1">⭐ Buruk (1)</option>
                            </select>
                        </div>
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Ulasan</label>
                            <textarea name="comment" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none" placeholder="Tuliskan ulasan kinerja kandidat ini..." required></textarea>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Ulasan',
                cancelButtonText: 'Batal',
                customClass: {
                    title: 'text-xl font-heading font-black text-slate-800',
                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm mx-2',
                    cancelButton: 'px-6 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 mx-2'
                },
                buttonsStyling: false,
                preConfirm: () => {
                    document.getElementById('reviewForm').submit();
                }
            });
        });
    });
</script>
@endsection
