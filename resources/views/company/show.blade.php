@extends('layouts.dashboard')

@section('title', 'Profil: ' . ($profile->name ?? $user->name))
@section('user_name', Auth::user()->name ?? 'Pengguna')
@section('user_role', Auth::user()->role === 'employer' ? 'Pemberi Kerja' : 'Kandidat Profesional')

@section('sidebar_menu')
    @if(Auth::user()->role === 'jobseeker')
        <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
        <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
        <li><a href="{{ route('jobseeker.applications.index') }}" class="sidebar-link"><i class="fa-solid fa-paper-plane w-5 text-center"></i> Lamaran Saya</a></li>
        <li><a href="{{ route('jobseeker.saved.index') }}" class="sidebar-link"><i class="fa-solid fa-bookmark w-5 text-center"></i> Tersimpan</a></li>
    @else
        <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
        <li><a href="{{ route('employer.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-list-check w-5 text-center"></i> Lowongan Aktif</a></li>
        <li><a href="{{ route('employer.candidates.index') }}" class="sidebar-link"><i class="fa-solid fa-users-viewfinder w-5 text-center"></i> Kandidat Masuk</a></li>
    @endif
    <li>
        <a href="{{ Auth::user()->role === 'employer' ? route('employer.messages.index') : route('jobseeker.messages.index') }}" class="sidebar-link">
            <i class="fa-solid fa-comment-dots w-5 text-center"></i> Pesan
        </a>
    </li>
    <li><a href="{{ Auth::user()->role === 'employer' ? route('employer.profile.index') : route('jobseeker.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-user w-5 text-center"></i> Profil Saya</a></li>
@endsection

@section('styles')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    .glass-card-premium {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 font-medium text-sm shadow-sm">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span>{{ session('success') }}</span>
</div>
@endif


<div class="rounded-3xl overflow-hidden mb-10 relative shadow-lg shadow-blue-900/5 group">
    
    <div class="h-64 sm:h-72 w-full relative overflow-hidden bg-[#0f172a]">
        <div class="absolute top-[-50%] left-[-10%] w-[70%] h-[200%] bg-blue-600/30 rounded-full blur-[80px] mix-blend-screen animate-float"></div>
        <div class="absolute top-[-20%] right-[-20%] w-[60%] h-[150%] bg-indigo-500/30 rounded-full blur-[100px] mix-blend-screen animate-float" style="animation-delay: -3s;"></div>
        <div class="absolute bottom-[-50%] left-[20%] w-[50%] h-[150%] bg-purple-500/20 rounded-full blur-[90px] mix-blend-screen animate-float" style="animation-delay: -1.5s;"></div>
        
        
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-30 mix-blend-overlay"></div>
    </div>
    
    <div class="px-8 sm:px-12 pb-10 bg-white relative">
        <div class="flex flex-col md:flex-row gap-6 items-start md:items-end -mt-20 relative z-10">
            
            <div class="relative group cursor-pointer">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2rem] blur opacity-25 group-hover:opacity-60 transition duration-500"></div>
                <div class="w-36 h-36 sm:w-40 sm:h-40 rounded-[2rem] bg-white p-2 relative shadow-2xl overflow-hidden shrink-0 transform transition-transform duration-500 hover:scale-105">
                    <img src="{{ $profile?->logo ? asset('uploads/' . $profile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($profile?->name ?? $user->name).'&background=f8fafc&color=0f172a&size=200' }}" class="w-full h-full object-cover rounded-[1.5rem]">
                </div>
            </div>
            
            <div class="flex-1 pb-2 pt-4 md:pt-0">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-black uppercase tracking-widest mb-3 border border-blue-100">
                    <i class="fa-solid fa-briefcase text-[10px]"></i> {{ $profile?->industry ?? 'Pemberi Kerja / Perorangan' }}
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-2">
                    <h1 class="font-heading font-black text-4xl sm:text-5xl text-slate-800 tracking-tight">{{ $profile?->name ?? $user->name }}</h1>
                    @if($profile?->verified)
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-500" title="Terverifikasi Resmi">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-4 text-sm font-medium text-slate-500 mt-2">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-dot text-slate-400"></i> {{ $profile?->address ?? 'Lokasi tidak disebutkan' }}</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    <span class="flex items-center gap-1.5 text-emerald-600"><i class="fa-solid fa-circle-check"></i> Bergabung sejak {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
            
            <div class="pb-2 w-full md:w-auto mt-4 md:mt-0 flex gap-3">
                <a href="{{ Auth::user()->role === 'jobseeker' ? route('jobseeker.jobs.index') : '#' }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-2xl shadow-sm transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        
        <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="col-span-1 lg:col-span-2 space-y-8">
                
                <div>
                    <h3 class="font-heading font-black text-2xl text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i> Tentang Kami
                    </h3>
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-slate-600 leading-relaxed text-sm md:text-base whitespace-pre-wrap">{{ $profile?->description ?? 'Belum ada deskripsi profil yang ditambahkan oleh pengguna ini.' }}</div>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="glass-card-premium p-6 rounded-3xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-100 to-transparent rounded-bl-full -z-10 opacity-50 transition-transform group-hover:scale-110"></div>
                    
                    <h3 class="font-heading font-black text-lg text-slate-800 mb-5">Sekilas Info</h3>
                    
                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Lowongan Aktif</div>
                                <div class="font-black text-lg text-slate-800">{{ $jobs->count() }} Posisi</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Situs Web</div>
                                @if($profile?->website)
                                <a href="{{ str_starts_with($profile->website, 'http') ? $profile->website : 'https://' . $profile->website }}" target="_blank" class="font-bold text-blue-600 hover:underline flex items-center gap-1">{{ parse_url(str_starts_with($profile->website, 'http') ? $profile->website : 'https://' . $profile->website, PHP_URL_HOST) ?? $profile->website }} <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                                @else
                                <div class="font-bold text-slate-700">-</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h3 class="font-heading font-black text-3xl text-slate-800 mb-1">Peluang Karir</h3>
        <p class="text-slate-500 font-medium">Jelajahi {{ $jobs->count() }} posisi terbuka di {{ $profile?->name ?? $user->name }}.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($jobs as $job)
    <div class="glass-card-premium p-6 rounded-[2rem] card-hover flex flex-col group relative overflow-hidden bg-white/80">
        
        <div class="absolute -inset-4 bg-gradient-to-r from-blue-600/5 to-indigo-600/5 opacity-0 group-hover:opacity-100 transition duration-500 blur-xl pointer-events-none"></div>

        @if(Auth::user()->role === 'jobseeker')
        
        <form action="{{ route('jobseeker.saved.store', $job->id) }}" method="POST" class="absolute top-6 right-6 z-10">
            @csrf
            <button type="submit" class="w-10 h-10 rounded-full {{ in_array($job->id, $savedJobIds ?? []) ? 'bg-rose-100 text-rose-600 hover:bg-slate-100 hover:text-slate-500' : 'bg-slate-100 text-slate-400 hover:bg-rose-100 hover:text-rose-600' }} flex items-center justify-center transition-all shadow-sm" title="{{ in_array($job->id, $savedJobIds ?? []) ? 'Hapus dari Tersimpan' : 'Simpan' }}">
                <i class="{{ in_array($job->id, $savedJobIds ?? []) ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i>
            </button>
        </form>
        @endif
        
        <h3 class="font-heading font-black text-slate-800 text-xl group-hover:text-blue-600 transition-colors leading-tight mb-4 pr-12 relative z-10">{{ $job->title }}</h3>
        
        <div class="flex flex-wrap gap-2 mb-5 relative z-10">
            <span class="px-3 py-1.5 text-xs font-black uppercase tracking-wide rounded-lg bg-blue-50 text-blue-600 border border-blue-100">
                {{ $job->type }}
            </span>
            <span class="px-3 py-1.5 text-xs font-black uppercase tracking-wide rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                {{ $job->salary }}
            </span>
        </div>
        
        <div class="text-sm text-slate-500 font-medium line-clamp-3 mb-8 flex-1 relative z-10">{{ $job->description }}</div>
        
        <div class="mt-auto pt-5 border-t border-slate-100/80 flex justify-between items-center relative z-10">
            <div class="text-xs font-bold text-slate-400 flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg"><i class="fa-solid fa-location-dot text-slate-300"></i> {{ explode(',', $job->location)[0] }}</div>
            
            @if(Auth::user()->role === 'jobseeker')
            <form action="{{ route('jobseeker.jobs.apply', $job->id) }}" method="POST" class="apply-form">
                @csrf
                <input type="hidden" name="cover_letter" class="cover-letter-input">
                <button type="button" class="btn-apply px-5 py-2 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-sm font-bold shadow-md transition-all flex items-center gap-2 hover:-translate-y-0.5">
                    Lamar <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 px-6 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-white text-slate-300 rounded-full flex items-center justify-center text-4xl mb-6 shadow-sm border border-slate-100">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <h2 class="text-2xl font-heading font-black text-slate-800 mb-2">Belum Ada Lowongan Aktif</h2>
        <p class="text-slate-500 max-w-md mx-auto font-medium">Perusahaan atau individu ini sedang tidak membuka lowongan pekerjaan baru saat ini.</p>
    </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-apply').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.apply-form');
            const jobTitle = this.closest('.glass-card-premium').querySelector('h3').innerText;
            
            Swal.fire({
                title: 'Lamar Posisi',
                html: `<div class="text-left text-sm mb-4 text-slate-500 font-medium">Anda akan mengirimkan profil ke posisi: <br><span class="font-black text-slate-800 text-lg">${jobTitle}</span></div>
                       <div class="text-left text-sm mb-2 font-bold text-slate-700">Pesan Singkat (Opsional):</div>`,
                input: 'textarea',
                inputPlaceholder: 'Tuliskan cover letter pendek atau mengapa Anda cocok untuk posisi ini...',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Kirim Lamaran <i class="fa-solid fa-paper-plane ml-1"></i>',
                cancelButtonText: 'Batal',
                customClass: {
                    title: 'text-2xl font-heading font-black text-slate-800',
                    input: 'border-slate-200 rounded-2xl focus:ring-blue-500 focus:border-blue-500 text-sm p-4 min-h-[120px] bg-slate-50',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold text-white bg-slate-900 hover:bg-blue-600 shadow-md mx-2 transition-colors',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold text-white bg-slate-400 hover:bg-slate-500 shadow-sm mx-2 transition-colors'
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
