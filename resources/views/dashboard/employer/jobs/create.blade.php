@extends('layouts.dashboard')

@section('title', 'Pasang Lowongan Baru')
@section('user_name', Auth::user()->name ?? 'Pemberi Kerja')
@section('user_role', 'Level 1 Recruiter')

@section('sidebar_menu')
    <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
    <li><a href="{{ route('employer.jobs.create') }}" class="sidebar-link active"><i class="fa-solid fa-bullhorn w-5 text-center"></i> Pasang Lowongan</a></li>
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
    <li><a href="{{ route('employer.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-building w-5 text-center"></i> Profil Perusahaan</a></li>
    <li><a href="{{ route('employer.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass-panel p-8 rounded-2xl relative overflow-hidden">
        
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="mb-8 flex items-center justify-between border-b border-slate-100 pb-6">
            <div>
                <h2 class="text-2xl font-heading font-black text-slate-800">Detail Lowongan</h2>
                <p class="text-slate-500 text-sm mt-1">Lengkapi form di bawah untuk mendapatkan pelamar terbaik.</p>
            </div>
            <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-lg text-sm font-bold shadow-sm">
                <i class="fa-solid fa-star"></i> Misi: +100 XP
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl text-sm font-medium">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employer.jobs.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Posisi / Judul Pekerjaan</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Staff Administrasi, Kurir, dll" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Kategori Pekerjaan</label>
                    <input type="text" id="categoryInput" name="category" value="{{ old('category') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: IT, Logistik, F&B" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Tipe Pekerjaan</label>
                    <div class="relative">
                        <select name="type" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium" required>
                            <option value="Full-time">Full-time (Penuh Waktu)</option>
                            <option value="Part-time">Part-time (Paruh Waktu)</option>
                            <option value="Freelance">Freelance (Lepas)</option>
                            <option value="Internship">Internship (Magang)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Gaji Angka (Tanpa Titik) <button type="button" id="btnCheckWage" class="text-xs text-blue-600 hover:underline ml-2"><i class="fa-solid fa-calculator"></i> Cek Rata-rata</button></label>
                    <input type="number" id="baseSalaryInput" name="base_salary" value="{{ old('base_salary') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 3000000" required>
                    <p id="avgWageResult" class="text-xs text-slate-500 hidden mt-1"></p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Gaji Teks (Tampil)</label>
                    <input type="text" name="salary" value="{{ old('salary') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Rp 3.000.000" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Kebutuhan (Orang)</label>
                    <input type="number" name="quota" value="{{ old('quota', 1) }}" min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 1, 5" required>
                </div>
            </div>

            <input type="hidden" name="location" id="finalLocation" value="{{ old('location') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 relative">
                    <label class="block text-sm font-bold text-slate-700">Provinsi Lokasi Kerja</label>
                    <select id="provinsiSelect" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium text-slate-700">
                        <option value="">Memuat Provinsi...</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-11 text-slate-400 pointer-events-none"></i>
                </div>
                <div class="space-y-2 relative">
                    <label class="block text-sm font-bold text-slate-700">Kabupaten / Kota Lokasi Kerja</label>
                    <select id="kotaSelect" disabled class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium text-slate-700 disabled:opacity-50">
                        <option value="">Pilih Provinsi Dahulu</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-11 text-slate-400 pointer-events-none"></i>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Deskripsi & Syarat Pekerjaan</label>
                <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Jelaskan detail pekerjaan, tanggung jawab, dan kualifikasi yang dibutuhkan..." required>{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('employer.dashboard') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</a>
                <button type="submit" id="submitBtn" class="px-8 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Publikasikan Lowongan
                </button>
            </div>
        </form>
    </div>
</div>

<script>

    const provSelect = document.getElementById('provinsiSelect');
    const kotaSelect = document.getElementById('kotaSelect');
    const finalLocation = document.getElementById('finalLocation');
    const submitBtn = document.getElementById('submitBtn');


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
        } else {
            finalLocation.value = '';
        }
    });


    document.querySelector('form').addEventListener('submit', function(e) {
        if (!finalLocation.value) {
            e.preventDefault();
            alert('Mohon pilih Provinsi dan Kabupaten/Kota lokasi kerja.');
        }
    });

    // Check Average Wage
    document.getElementById('btnCheckWage').addEventListener('click', function() {
        const cat = document.getElementById('categoryInput').value;
        const resLabel = document.getElementById('avgWageResult');
        if(!cat) {
            alert('Silakan isi Kategori Pekerjaan terlebih dahulu.');
            return;
        }
        resLabel.classList.remove('hidden');
        resLabel.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menghitung...';

        fetch(`{{ route('employer.jobs.average-wage') }}?category=${encodeURIComponent(cat)}`)
            .then(res => res.json())
            .then(data => {
                if(data.average > 0) {
                    resLabel.innerHTML = `<span class="text-emerald-600 font-bold">Rata-rata: Rp ${parseInt(data.average).toLocaleString('id-ID')}</span>`;
                    document.getElementById('baseSalaryInput').value = parseInt(data.average);
                } else {
                    resLabel.innerHTML = `<span class="text-amber-600">Belum ada data gaji untuk kategori ini.</span>`;
                }
            })
            .catch(() => {
                resLabel.innerHTML = `<span class="text-rose-500">Gagal mengambil data.</span>`;
            });
    });
</script>
@endsection
