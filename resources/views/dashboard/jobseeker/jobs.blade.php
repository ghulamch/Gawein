@extends('layouts.dashboard')

@section('title', 'Cari Lowongan')
@section('user_name', Auth::user()->name ?? 'Pencari Kerja')
@section('user_role', 'Kandidat Profesional')

@section('sidebar_menu')
    <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link active"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
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

<div class="glass-panel p-6 rounded-2xl mb-8">
    <form action="{{ route('jobseeker.jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-4 top-3.5 text-slate-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none font-medium text-slate-700" placeholder="Cari posisi, perusahaan, atau keahlian...">
        </div>
        <div class="w-full md:w-64 relative" title="Lokasi (kosongkan untuk semua)">
            <i class="fa-solid fa-location-dot absolute left-4 top-3.5 text-slate-400"></i>
            <input type="text" name="location" value="{{ request('location', $domicile ?? '') }}" placeholder="Kota / Domisili..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none font-medium text-slate-700">
        </div>
        <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md shadow-blue-600/20 transition-colors whitespace-nowrap">
            Cari
        </button>
    </form>
    
    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100 overflow-x-auto pb-1">
        <span class="text-xs font-bold text-slate-400 uppercase mr-2">Filter Populer:</span>
        @forelse($popularFilters ?? [] as $filter)
            <a href="{{ route('jobseeker.jobs.index', ['search' => $filter]) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 cursor-pointer whitespace-nowrap">
                {{ $filter }}
            </a>
        @empty
            <a href="{{ route('jobseeker.jobs.index', ['search' => 'Remote']) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 cursor-pointer whitespace-nowrap">Remote</a>
            <a href="{{ route('jobseeker.jobs.index', ['search' => 'Full-time']) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 cursor-pointer whitespace-nowrap">Full-time</a>
        @endforelse
    </div>
</div>

<div class="mb-4 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
    <div class="text-sm font-bold text-slate-500">
        Menampilkan {{ $jobs->count() }} lowongan aktif
    </div>
    
    @if($domicile)
    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-100 text-blue-600 rounded-lg text-xs font-bold shadow-sm">
        <i class="fa-solid fa-location-crosshairs text-blue-500"></i> Disaring berdasarkan domisili Anda ({{ $domicile }})
    </div>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($jobs as $job)
    <div class="glass-panel p-6 rounded-2xl card-hover flex flex-col group relative overflow-hidden">
        
        <form action="{{ route('jobseeker.saved.store', $job->id) }}" method="POST" class="absolute top-4 right-4 z-10">
            @csrf
            <button type="submit" class="w-8 h-8 rounded-full {{ in_array($job->id, $savedJobIds ?? []) ? 'bg-rose-50 text-rose-500 hover:bg-slate-100 hover:text-slate-400' : 'bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500' }} flex items-center justify-center transition-colors" title="{{ in_array($job->id, $savedJobIds ?? []) ? 'Hapus dari Tersimpan' : 'Simpan' }}">
                <i class="{{ in_array($job->id, $savedJobIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i>
            </button>
        </form>
        
        <div class="flex items-start gap-4 mb-4">
            <a href="{{ route('employer.public.profile', $job->employer_id) }}" class="w-14 h-14 rounded-xl bg-white border border-slate-100 shadow-sm p-1 overflow-hidden shrink-0 hover:scale-105 transition-transform" title="Lihat Profil">
                <img src="{{ $job->employer?->companyProfile?->logo ? asset('uploads/' . $job->employer->companyProfile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($job->employer?->companyProfile?->name ?? 'Company').'&background=f1f5f9' }}" class="w-full h-full object-cover rounded-lg">
            </a>
            <div>
                <h3 class="font-heading font-black text-slate-800 text-lg group-hover:text-blue-600 transition-colors leading-tight">{{ $job->title }}</h3>
                <a href="{{ route('employer.public.profile', $job->employer_id) }}" class="text-sm font-semibold text-slate-500 mt-1 hover:text-blue-600 transition-colors inline-block">{{ $job->employer?->companyProfile?->name ?? 'Perusahaan' }}</a>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-blue-50 text-blue-600 border border-blue-100 flex items-center gap-1">
                <i class="fa-solid fa-briefcase"></i> {{ $job->type }}
            </span>
            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1">
                <i class="fa-solid fa-money-bill-wave"></i> {{ $job->salary }}
            </span>
            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-slate-50 text-slate-600 border border-slate-200 flex items-center gap-1">
                <i class="fa-solid fa-location-dot"></i> {{ $job->location }}
            </span>
        </div>
        
        <p class="text-sm text-slate-600 line-clamp-3 mb-6 flex-1">{{ $job->description }}</p>
        
        <div class="mt-auto pt-4 border-t border-slate-100 flex justify-between items-center">
            <div class="text-[11px] font-bold text-slate-400"><i class="fa-regular fa-clock"></i> {{ $job->created_at->diffForHumans() }}</div>
            <form action="{{ route('jobseeker.jobs.apply', $job->id) }}" method="POST" class="apply-form">
                @csrf
                <input type="hidden" name="cover_letter" class="cover-letter-input">
                <button type="button" class="btn-apply px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-sm shadow-blue-600/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Lamar Cepat
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full glass-panel p-12 rounded-2xl flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <h2 class="text-2xl font-heading font-black text-slate-800 mb-2">Belum Ada Lowongan</h2>
        <p class="text-slate-500 max-w-md mx-auto">Saat ini belum ada perusahaan yang memasang lowongan. Silakan periksa kembali nanti.</p>
    </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-apply').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.apply-form');
            const jobTitle = this.closest('.glass-panel').querySelector('h3').innerText;
            
            Swal.fire({
                title: 'Lamar Pekerjaan',
                html: `<div class="text-left text-sm mb-2 text-slate-600 font-medium">Posisi: <span class="font-bold text-slate-800">${jobTitle}</span></div>
                       <div class="text-left text-sm mb-2 text-slate-600">Pesan Singkat / Cover Letter (opsional):</div>`,
                input: 'textarea',
                inputPlaceholder: 'Perkenalkan diri Anda secara singkat...',
                inputAttributes: {
                    'aria-label': 'Ketik pesan Anda di sini'
                },
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-paper-plane mr-1"></i> Kirim Lamaran',
                cancelButtonText: 'Batal',
                customClass: {
                    title: 'text-xl font-heading font-bold text-slate-800',
                    input: 'border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm p-3 min-h-[100px]',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm mx-2',
                    cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-white bg-slate-500 hover:bg-slate-600 shadow-sm mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.querySelector('.cover-letter-input').value = result.value || '';
                    form.submit();
                }
            })
        });
    });
</script>
@endsection
