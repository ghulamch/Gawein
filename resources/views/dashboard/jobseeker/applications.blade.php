@extends('layouts.dashboard')

@section('title', 'Lamaran Saya')
@section('user_name', Auth::user()->name ?? 'Pencari Kerja')
@section('user_role', 'Kandidat Profesional')

@section('sidebar_menu')
    <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
    <li><a href="{{ route('jobseeker.applications.index') }}" class="sidebar-link active"><i class="fa-solid fa-paper-plane w-5 text-center"></i> Lamaran Saya</a></li>
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
@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
    <i class="fa-solid fa-circle-exclamation text-lg"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="glass-panel p-6 rounded-2xl flex flex-col mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-heading font-bold text-xl text-slate-800">Status Lamaran</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50">
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider rounded-tl-lg">Posisi & Perusahaan</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Melamar</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($applications as $app)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-100 p-1 shrink-0">
                                <img src="{{ $app->job->employer?->companyProfile?->logo ? asset('uploads/' . $app->job->employer->companyProfile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($app->job->employer?->companyProfile?->name ?? 'Company').'&background=f1f5f9' }}" class="w-full h-full object-cover rounded-md">
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $app->job->title }}</div>
                                <div class="text-[11px] font-semibold text-slate-500">{{ $app->job->employer?->companyProfile?->name ?? 'Perusahaan' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-sm font-semibold text-slate-700">
                        {{ $app->created_at->format('d M Y') }}
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-blue-50 text-blue-600 border border-blue-100">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="btn-detail px-4 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded hover:bg-slate-50 shadow-sm transition-all"
                                data-title="{{ $app->job->title }}"
                                data-company="{{ $app->job->employer?->companyProfile?->name ?? 'Perusahaan' }}"
                                data-date="{{ $app->created_at->format('d M Y, H:i') }}"
                                data-status="{{ strtoupper($app->status) }}"
                                data-type="{{ $app->job->type }}"
                                data-salary="{{ $app->job->salary }}"
                                data-coverletter="{{ $app->cover_letter ?? 'Tidak melampirkan cover letter.' }}"
                                data-logo="{{ $app->job->employer?->companyProfile?->logo ? asset('uploads/' . $app->job->employer->companyProfile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($app->job->employer?->companyProfile?->name ?? 'Company').'&background=f1f5f9' }}"
                            >Detail</button>
                            
                            @if($app->status === 'accepted' && $app->transaction_id)
                                @php
                                    $hasReviewed = \App\Models\Review::where('transaction_id', $app->transaction_id)->where('reviewer_id', Auth::id())->exists();
                                @endphp
                                @if(!$hasReviewed)
                                    <button class="btn-review px-4 py-1.5 bg-amber-500 text-white text-xs font-bold rounded hover:bg-amber-600 shadow-sm transition-all"
                                        data-transaction="{{ $app->transaction_id }}"
                                        data-reviewee="{{ $app->job->employer_id }}"
                                    ><i class="fa-solid fa-star"></i> Beri Ulasan</button>
                                @else
                                    <span class="px-4 py-1.5 bg-slate-100 text-slate-400 text-xs font-bold rounded cursor-not-allowed"><i class="fa-solid fa-check"></i> Diulas</span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        <div class="font-bold text-slate-600">Belum ada lamaran</div>
                        <div class="text-sm text-slate-500 mt-1 mb-4">Kamu belum mengirim lamaran ke perusahaan manapun.</div>
                        <a href="{{ route('jobseeker.jobs.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-700 transition-colors">Cari Lowongan</a>
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
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const company = this.getAttribute('data-company');
            const date = this.getAttribute('data-date');
            const status = this.getAttribute('data-status');
            const type = this.getAttribute('data-type');
            const salary = this.getAttribute('data-salary');
            const coverletter = this.getAttribute('data-coverletter');
            const logo = this.getAttribute('data-logo');

            let statusColor = 'bg-slate-100 text-slate-600';
            if(status === 'ACCEPTED') statusColor = 'bg-emerald-100 text-emerald-700';
            if(status === 'REJECTED') statusColor = 'bg-rose-100 text-rose-700';
            if(status === 'INTERVIEW') statusColor = 'bg-blue-100 text-blue-700';
            if(status === 'REVIEW') statusColor = 'bg-amber-100 text-amber-700';

            Swal.fire({
                title: 'Detail Lamaran',
                html: `
                    <div class="text-left mt-4 border-t border-slate-100 pt-4">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-14 h-14 rounded-xl border border-slate-200 p-1 bg-white shrink-0">
                                <img src="${logo}" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <div class="font-black text-lg text-slate-800">${title}</div>
                                <div class="font-medium text-slate-500 text-sm">${company}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Tanggal Melamar</div>
                                <div class="text-sm font-semibold text-slate-700">${date}</div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Status Saat Ini</div>
                                <div class="text-xs font-bold px-2 py-0.5 rounded ${statusColor} inline-block">${status}</div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Tipe Pekerjaan</div>
                                <div class="text-sm font-semibold text-slate-700">${type}</div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Gaji</div>
                                <div class="text-sm font-semibold text-slate-700">${salary}</div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="text-xs font-bold text-slate-500 mb-2">Cover Letter Anda:</div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600 max-h-48 overflow-y-auto whitespace-pre-wrap">${coverletter}</div>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                customClass: {
                    title: 'text-xl font-heading font-black text-slate-800',
                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 shadow-sm w-full mt-4'
                },
                buttonsStyling: false,
                width: '32rem'
            });
        });
    });

    document.querySelectorAll('.btn-review').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction');
            const revieweeId = this.getAttribute('data-reviewee');
            
            Swal.fire({
                title: 'Berikan Ulasan',
                html: `
                    <form id="reviewForm" method="POST" action="/transactions/${transactionId}/reviews">
                        @csrf
                        <input type="hidden" name="reviewee_id" value="${revieweeId}">
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rating (1-5)</label>
                            <select name="rating" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none" required>
                                <option value="5">⭐⭐⭐⭐⭐ Sangat Baik (5)</option>
                                <option value="4">⭐⭐⭐⭐ Baik (4)</option>
                                <option value="3">⭐⭐⭐ Cukup (3)</option>
                                <option value="2">⭐⭐ Kurang (2)</option>
                                <option value="1">⭐ Buruk (1)</option>
                            </select>
                        </div>
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Komentar / Pengalaman Kerja</label>
                            <textarea name="comment" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none" placeholder="Ceritakan pengalaman Anda bekerja dengan perusahaan ini..." required></textarea>
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
