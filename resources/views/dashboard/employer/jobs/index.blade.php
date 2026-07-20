@extends('layouts.dashboard')

@section('title', 'Lowongan Aktif')
@section('user_name', Auth::user()->name ?? 'Pemberi Kerja')
@section('user_role', 'Recruiter')

@section('sidebar_menu')
    <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('employer.jobs.create') }}" class="sidebar-link"><i class="fa-solid fa-bullhorn w-5 text-center"></i> Pasang Lowongan</a></li>
    <li><a href="{{ route('employer.jobs.index') }}" class="sidebar-link active"><i class="fa-solid fa-list-check w-5 text-center"></i> Lowongan Aktif</a></li>
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

<div class="glass-panel p-6 rounded-2xl flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-heading font-bold text-xl text-slate-800">Daftar Lowongan Anda</h3>
        <a href="{{ route('employer.jobs.create') }}" class="btn-action bg-blue-600 text-white hover:bg-blue-700 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Buat Baru
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50">
                    <th class="py-4 px-4 text-sm font-semibold text-slate-500 uppercase tracking-wider rounded-tl-lg">Posisi</th>
                    <th class="py-4 px-4 text-sm font-semibold text-slate-500 uppercase tracking-wider">Tipe</th>
                    <th class="py-4 px-4 text-sm font-semibold text-slate-500 uppercase tracking-wider">Gaji</th>
                    <th class="py-4 px-4 text-sm font-semibold text-slate-500 uppercase tracking-wider">Pelamar</th>
                    <th class="py-4 px-4 text-sm font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-4 text-sm font-semibold text-slate-500 uppercase tracking-wider text-right rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($jobs as $job)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-4">
                            <div class="font-bold text-slate-800 text-base group-hover:text-blue-600 transition-colors">{{ $job->title }}</div>
                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $job->location }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-slate-100 text-slate-600 border border-slate-200">{{ $job->type }}</span>
                        </td>
                        <td class="py-4 px-4 text-sm font-semibold text-slate-700">{{ $job->salary }}</td>
                        <td class="py-4 px-4">
                            @php
                                $totalApps = $job->applications()->count();
                                $acceptedApps = $job->applications()->where('status', 'accepted')->count();
                            @endphp
                            <div class="flex flex-col gap-1">
                                <div class="text-xs font-bold text-slate-700"><i class="fa-solid fa-users text-blue-500"></i> {{ $totalApps }} Pelamar</div>
                                <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded inline-block w-max">
                                    <i class="fa-solid fa-check-circle"></i> {{ $acceptedApps }} / {{ $job->quota }} Diterima
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            @if($job->status == 'active')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Aktif</span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200">Ditutup</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('employer.jobs.edit', $job->id) }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all flex items-center justify-center shadow-sm" title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('employer.jobs.destroy', $job->id) }}" method="POST" class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm" title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <div class="font-bold text-slate-600">Belum Ada Lowongan</div>
                            <div class="text-sm text-slate-500 mt-1 mb-4">Mulai pasang lowongan untuk mendapatkan kandidat.</div>
                            <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all">
                                <i class="fa-solid fa-plus"></i> Pasang Lowongan
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Lowongan?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                customClass: {
                    title: 'text-xl font-heading font-bold text-slate-800',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 shadow-sm mx-2',
                    cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-white bg-slate-500 hover:bg-slate-600 shadow-sm mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>
@endsection

