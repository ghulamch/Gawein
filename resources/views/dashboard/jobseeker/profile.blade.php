@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('user_name', Auth::user()->name ?? 'Pencari Kerja')
@section('user_role', 'Kandidat Profesional')

@section('sidebar_menu')
    <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
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
    <li><a href="{{ route('jobseeker.profile.index') }}" class="sidebar-link active"><i class="fa-solid fa-user w-5 text-center"></i> Profil Saya</a></li>
    <li><a href="{{ route('jobseeker.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
    <div class="mb-6 bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="glass-panel p-8 rounded-2xl mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -z-10"></div>
        
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="w-32 h-32 rounded-full bg-slate-100 border-4 border-white shadow-xl relative overflow-hidden group shrink-0">
                <img id="avatarPreview" src="{{ $profile->avatar ? asset('uploads/' . $profile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0f172a&color=fff&size=200' }}" class="w-full h-full object-cover">
                <div onclick="document.getElementById('avatarInput').click()" class="absolute inset-0 bg-slate-900/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white">
                    <i class="fa-solid fa-camera text-xl mb-1"></i>
                    <span class="text-[10px] font-bold">Ubah Foto</span>
                </div>
            </div>
            
            <div class="flex-1 w-full">
                <h2 class="text-2xl font-heading font-black text-slate-800 mb-1">{{ Auth::user()->name }}</h2>
                <div class="text-sm font-semibold text-blue-600 mb-4">{{ $profile->title ?: 'Tambahkan Judul Profesional (mis: Web Developer)' }}</div>
                
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelengkapan Profil</span>
                            <span class="text-sm font-black text-slate-800">{{ $profile->profile_completion ?? 10 }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: {{ $profile->profile_completion ?? 10 }}%"></div>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Lengkapi profil untuk meningkatkan peluang dilirik HRD hingga 80%.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('jobseeker.profile.update') }}" method="POST" enctype="multipart/form-data" class="glass-panel p-8 rounded-2xl space-y-8">
        @csrf
        <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/jpeg,image/png,image/jpg">
        <input type="hidden" name="location" id="finalLocation" value="{{ $profile->location }}">

        <div>
            <h3 class="font-heading font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-6">Informasi Dasar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Profesional</label>
                    <input type="text" name="title" value="{{ $profile->title }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700" placeholder="Contoh: Digital Marketer, Graphic Designer">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="space-y-2 relative">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Provinsi</label>
                    <select id="provinsiSelect" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium text-slate-700">
                        <option value="">Memuat Provinsi...</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-[38px] text-slate-400 pointer-events-none"></i>
                </div>
                <div class="space-y-2 relative">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Kabupaten / Kota</label>
                    <select id="kotaSelect" disabled class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium text-slate-700 disabled:opacity-50">
                        <option value="">Pilih Provinsi Dahulu</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-[38px] text-slate-400 pointer-events-none"></i>
                </div>
            </div>
            @if($profile->location)
            <div class="mt-2 text-xs text-emerald-600 font-bold"><i class="fa-solid fa-check-circle"></i> Domisili saat ini: {{ $profile->location }} (Pilih ulang jika ingin mengubah)</div>
            @endif
            
            <div class="space-y-2 mt-6">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Tentang Saya (Ringkasan)</label>
                <textarea name="about" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700" placeholder="Ceritakan secara singkat tentang pengalaman, passion, dan karir objektifmu...">{{ $profile->about }}</textarea>
            </div>
        </div>

        <div>
            <h3 class="font-heading font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-6">Keahlian & Kemampuan</h3>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Skills (Keahlian Utama)</label>
                <input type="text" name="skills" value="{{ $profile->skills }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700" placeholder="Pisahkan dengan koma. Contoh: Microsoft Word, Desain Grafis, Public Speaking">
            </div>
        </div>

        <div>
            <h3 class="font-heading font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-6">Resume / Curriculum Vitae (CV)</h3>
            <label class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer group relative">
                <input type="file" name="resume" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx" id="resumeInput">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-2xl text-blue-500 mb-3 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div class="font-bold text-slate-700" id="resumeLabel">
                    {{ $profile->resume ? 'Ganti CV' : 'Klik untuk mengunggah CV Anda' }}
                </div>
                <div class="text-xs text-slate-500 mt-1">Mendukung file PDF, DOCX (Maksimal 5MB)</div>
                @if($profile->resume)
                    <div class="mt-4 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-bold w-full"><i class="fa-solid fa-file-pdf"></i> CV Tersimpan</div>
                @endif
            </label>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 group">
                <i class="fa-solid fa-floppy-disk group-hover:scale-110 transition-transform"></i> Simpan Profil
            </button>
        </div>
    </form>
</div>

<script>

    document.getElementById('avatarInput').addEventListener('change', function(e) {
        if(e.target.files[0]) {
            document.getElementById('avatarPreview').src = URL.createObjectURL(e.target.files[0]);
        }
    });


    document.getElementById('resumeInput').addEventListener('change', function(e) {
        if(e.target.files[0]) {
            document.getElementById('resumeLabel').innerHTML = '<i class="fa-solid fa-file-check"></i> ' + e.target.files[0].name;
            document.getElementById('resumeLabel').classList.add('text-blue-600');
        }
    });


    const provSelect = document.getElementById('provinsiSelect');
    const kotaSelect = document.getElementById('kotaSelect');
    const finalLocation = document.getElementById('finalLocation');


    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(response => response.json())
        .then(provinces => {
            provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
            provinces.forEach(prov => {
                provSelect.innerHTML += `<option value="${prov.id}" data-name="${prov.name}">${prov.name}</option>`;
            });
        });

    provSelect.addEventListener('change', function() {
        if(!this.value) {
            kotaSelect.innerHTML = '<option value="">Pilih Provinsi Dahulu</option>';
            kotaSelect.disabled = true;
            return;
        }
        
        kotaSelect.disabled = false;
        kotaSelect.innerHTML = '<option value="">Memuat Kota...</option>';
        
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`)
            .then(response => response.json())
            .then(regencies => {
                kotaSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
                regencies.forEach(reg => {
                    kotaSelect.innerHTML += `<option value="${reg.id}" data-name="${reg.name}">${reg.name}</option>`;
                });
            });
    });

    kotaSelect.addEventListener('change', function() {
        if(this.value && provSelect.value) {
            const provName = provSelect.options[provSelect.selectedIndex].getAttribute('data-name');
            const kotaName = kotaSelect.options[kotaSelect.selectedIndex].getAttribute('data-name');
            finalLocation.value = kotaName + ', ' + provName;
        }
    });
</script>
@endsection
