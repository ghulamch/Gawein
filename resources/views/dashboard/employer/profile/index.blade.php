@extends('layouts.dashboard')

@section('title', 'Profil Perusahaan')
@section('user_name', Auth::user()->name ?? 'Pemberi Kerja')
@section('user_role', 'Level ' . ($profile->level ?? 1) . ' Recruiter')

@section('sidebar_menu')
    <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
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
    <li><a href="{{ route('employer.profile.index') }}" class="sidebar-link active"><i class="fa-solid fa-building w-5 text-center"></i> Profil Perusahaan</a></li>
    <li><a href="{{ route('employer.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')
<div class="max-w-5xl mx-auto pb-10">
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Profil Diperbarui!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563eb',
                customClass: {
                    title: 'font-heading text-xl text-slate-800 font-black',
                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold bg-blue-600 hover:bg-blue-700 shadow-sm'
                }
            });
        });
    </script>
    @endif

    @if ($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 p-4 rounded-2xl flex items-start gap-4">
        <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="font-bold text-rose-800 text-sm mb-1">Terjadi Kesalahan</h4>
            <ul class="text-sm text-rose-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('employer.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        
        
        <div class="relative w-full h-[320px] rounded-[2rem] overflow-hidden mb-24 shadow-sm group bg-slate-900 transition-all">
            
            @if(!$profile->cover_photo)
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-400 via-blue-600 to-slate-900 opacity-80"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+CjxjaXJjbGUgY3g9IjIiIGN5PSIyIiByPSIyIiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+Cjwvc3ZnPg==')] opacity-30"></div>
            @endif

            <img id="coverPreview" src="{{ $profile->cover_photo ? asset('uploads/' . $profile->cover_photo) : '' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" style="display: {{ $profile->cover_photo ? 'block' : 'none' }}">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
            
            <input type="file" name="cover_photo" id="coverInput" class="hidden" accept="image/jpeg,image/png,image/jpg">
            
            <div class="absolute top-6 right-6">
                <button type="button" onclick="document.getElementById('coverInput').click()" class="bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg">
                    <i class="fa-solid fa-pen-to-square"></i> Ubah Foto Sampul
                </button>
            </div>
            
            <div class="absolute bottom-6 right-6 bg-black/40 backdrop-blur-md text-white/90 px-4 py-2 rounded-xl text-xs font-semibold border border-white/10 hidden md:block">
                <i class="fa-solid fa-circle-info mr-1"></i> Disarankan 1920x820px
            </div>

            
            <div class="absolute -bottom-16 left-8 md:left-12 flex items-end gap-6 z-20">
                <div class="relative w-40 h-40 group/avatar cursor-pointer" onclick="document.getElementById('logoInput').click()">
                    <div class="w-full h-full rounded-2xl bg-white p-1.5 shadow-2xl relative overflow-hidden transition-transform duration-300 group-hover/avatar:scale-105 group-hover/avatar:-translate-y-2">
                        <img id="logoPreview" src="{{ $profile->logo ? asset('uploads/' . $profile->logo) : 'https://ui-avatars.com/api/?name='.urlencode($profile->name).'&background=f1f5f9&color=0f172a&size=200' }}" class="w-full h-full rounded-xl object-cover bg-slate-100">
                        <div class="absolute inset-0 bg-slate-900/50 flex flex-col items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-opacity text-white m-1.5 rounded-xl">
                            <i class="fa-solid fa-camera text-2xl mb-1"></i>
                            <span class="text-xs font-bold">Ubah Logo</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-600 rounded-xl text-white flex items-center justify-center shadow-lg border-2 border-white">
                        <i class="fa-solid fa-pen text-sm"></i>
                    </div>
                    <input type="file" name="logo" id="logoInput" class="hidden" accept="image/jpeg,image/png,image/jpg">
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-2">
            
            
            <div class="lg:col-span-2 space-y-8">
                
                
                <div class="glass-panel p-8 md:p-10 rounded-[2rem]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <h3 class="font-heading font-black text-2xl text-slate-800">Identitas Perusahaan</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Perusahaan</label>
                                <div class="relative">
                                    <i class="fa-solid fa-building absolute left-5 top-4 text-slate-400"></i>
                                    <input type="text" name="company_name" value="{{ old('company_name', $profile->name) }}" class="w-full pl-12 pr-5 py-3.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700" placeholder="Nama perusahaan Anda" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Akun (Tampil di Sistem)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-user absolute left-5 top-4 text-slate-400"></i>
                                    <input type="text" name="account_name" value="{{ old('account_name', Auth::user()->name) }}" class="w-full pl-12 pr-5 py-3.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700" placeholder="Nama yang tampil di akun" required>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Industri</label>
                                <div class="relative">
                                    <select name="industry" class="w-full px-5 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none font-semibold text-slate-700 transition-all cursor-pointer">
                                        <option value="" disabled {{ !$profile->industry ? 'selected' : '' }}>Pilih Industri...</option>
                                        <option value="Teknologi & Startup" {{ $profile->industry == 'Teknologi & Startup' ? 'selected' : '' }}>Teknologi & Startup</option>
                                        <option value="F&B (Makanan & Minuman)" {{ $profile->industry == 'F&B (Makanan & Minuman)' ? 'selected' : '' }}>F&B (Makanan & Minuman)</option>
                                        <option value="Ritel & Perdagangan" {{ $profile->industry == 'Ritel & Perdagangan' ? 'selected' : '' }}>Ritel & Perdagangan</option>
                                        <option value="Pendidikan" {{ $profile->industry == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                        <option value="Kesehatan" {{ $profile->industry == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                        <option value="Lainnya" {{ $profile->industry == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-5 top-4 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun Berdiri</label>
                                <div class="relative">
                                    <i class="fa-regular fa-calendar absolute left-5 top-4 text-slate-400"></i>
                                    <input type="number" name="year_founded" value="{{ old('year_founded', $profile->year_founded) }}" class="w-full pl-12 pr-5 py-3.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700" placeholder="Contoh: 2020">
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Website / Portofolio Utama</label>
                            <div class="relative">
                                <i class="fa-solid fa-globe absolute left-5 top-4 text-slate-400"></i>
                                <input type="url" name="website" value="{{ old('website', $profile->website) }}" class="w-full pl-12 pr-5 py-3.5 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-blue-600 font-bold" placeholder="https://www.perusahaan.com">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="glass-panel p-8 md:p-10 rounded-[2rem]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-align-left"></i>
                        </div>
                        <h3 class="font-heading font-black text-2xl text-slate-800">Tentang Perusahaan</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Singkat</p>
                        <textarea name="description" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-5 text-sm text-slate-700 leading-loose min-h-[200px] outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all focus:bg-white resize-y" placeholder="Ceritakan sejarah singkat, visi, misi, serta lingkungan kerja di tempat Anda untuk menarik kandidat terbaik...">{{ old('description', $profile->description) }}</textarea>
                    </div>
                </div>
                
                
                <div class="flex justify-end gap-4 mt-8 sticky bottom-10 z-30">
                    <button type="submit" class="px-8 py-4 rounded-2xl font-black text-white bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-600/30 transition-all flex items-center gap-2 hover:-translate-y-1">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Simpan Semua Perubahan
                    </button>
                </div>
            </div>
            
            
            <div class="space-y-8">
                
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-8 rounded-[2rem] shadow-2xl relative overflow-hidden text-white">
                    <div class="absolute -right-6 -bottom-6 text-white/5 text-[150px] rotate-12 pointer-events-none">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <h4 class="font-heading font-black text-xl mb-6">Kepercayaan Profil</h4>
                        
                        @php
                            $score = 25;
                            if($profile->description) $score += 25;
                            if($profile->industry) $score += 20;
                            if($profile->logo) $score += 15;
                            if($profile->cover_photo) $score += 15;
                        @endphp
                        
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-sm font-semibold text-slate-400">Skor Kelengkapan</span>
                            <span class="text-3xl font-black text-emerald-400">{{ $score }}<span class="text-lg text-emerald-500/80">%</span></span>
                        </div>
                        
                        <div class="w-full bg-slate-700/50 h-3 rounded-full overflow-hidden mb-8 backdrop-blur-sm">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-300 rounded-full shadow-[0_0_15px_rgba(52,211,153,0.5)] transition-all duration-1000" style="width: {{ $score }}%"></div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-sm {{ $profile->logo ? 'text-slate-300' : 'text-slate-500' }}">
                                <i class="fa-solid fa-circle-check {{ $profile->logo ? 'text-emerald-400' : 'text-slate-600' }}"></i> Unggah Logo Perusahaan
                            </div>
                            <div class="flex items-center gap-3 text-sm {{ $profile->cover_photo ? 'text-slate-300' : 'text-slate-500' }}">
                                <i class="fa-solid fa-circle-check {{ $profile->cover_photo ? 'text-emerald-400' : 'text-slate-600' }}"></i> Pasang Foto Sampul
                            </div>
                            <div class="flex items-center gap-3 text-sm {{ $profile->description ? 'text-slate-300' : 'text-slate-500' }}">
                                <i class="fa-solid fa-circle-check {{ $profile->description ? 'text-emerald-400' : 'text-slate-600' }}"></i> Tulis Deskripsi Perusahaan
                            </div>
                            <div class="flex items-center gap-3 text-sm {{ $profile->industry ? 'text-slate-300' : 'text-slate-500' }}">
                                <i class="fa-solid fa-circle-check {{ $profile->industry ? 'text-emerald-400' : 'text-slate-600' }}"></i> Lengkapi Detail Industri
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="glass-panel p-8 rounded-[2rem] border border-blue-100 bg-gradient-to-b from-white to-blue-50/50">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-heading font-black text-lg text-slate-800">Tingkat Recruiter</h4>
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-500 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mb-6">
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Level Saat Ini</div>
                        <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Level {{ $profile->level ?? 1 }}</div>
                    </div>
                    
                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                        <span>{{ $profile->xp ?? 0 }} XP</span>
                        <span>{{ ($profile->level ?? 1) * 500 }} XP</span>
                    </div>
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden mb-4">
                        <div class="bg-blue-600 h-full rounded-full" style="width: {{ (($profile->xp ?? 0) / (($profile->level ?? 1) * 500)) * 100 }}%"></div>
                    </div>
                    
                    <p class="text-xs text-slate-500 font-medium leading-relaxed text-center">Tingkatkan terus profil Anda dan sering berinteraksi dengan pelamar untuk naik level. (+50 XP per update)</p>
                </div>
            </div>
            
        </div>
    </form>
</div>


<script>
    function setupPreview(inputId, previewId) {
        document.getElementById(inputId).addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: 'info',
                        title: 'Foto siap, jangan lupa klik Simpan!'
                    });
                }
                reader.readAsDataURL(file);
            }
        });
    }
    setupPreview('coverInput', 'coverPreview');
    setupPreview('logoInput', 'logoPreview');
</script>
@endsection
