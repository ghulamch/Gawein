@extends('layouts.dashboard')

@section('title', 'Edit Lowongan')
@section('user_name', Auth::user()->name ?? 'Pemberi Kerja')
@section('user_role', 'Level 1 Recruiter')

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
<div class="max-w-3xl mx-auto">
    <div class="glass-panel p-8 rounded-2xl relative overflow-hidden">
        
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="mb-8 flex items-center justify-between border-b border-slate-100 pb-6">
            <div>
                <h2 class="text-2xl font-heading font-black text-slate-800">Edit Lowongan: {{ $job->title }}</h2>
                <p class="text-slate-500 text-sm mt-1">Perbarui informasi lowongan pekerjaan Anda.</p>
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

        <form action="{{ route('employer.jobs.update', $job->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Posisi / Judul Pekerjaan</label>
                    <input type="text" name="title" value="{{ old('title', $job->title) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Staff Administrasi, Kurir, dll" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Kategori Pekerjaan</label>
                    <input type="text" id="categoryInput" name="category" value="{{ old('category', $job->category) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: IT, Logistik, F&B" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Tipe Pekerjaan</label>
                    <div class="relative">
                        <select name="type" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium" required>
                            <option value="Full-time" {{ old('type', $job->type) == 'Full-time' ? 'selected' : '' }}>Full-time (Penuh Waktu)</option>
                            <option value="Part-time" {{ old('type', $job->type) == 'Part-time' ? 'selected' : '' }}>Part-time (Paruh Waktu)</option>
                            <option value="Freelance" {{ old('type', $job->type) == 'Freelance' ? 'selected' : '' }}>Freelance (Lepas)</option>
                            <option value="Internship" {{ old('type', $job->type) == 'Internship' ? 'selected' : '' }}>Internship (Magang)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Gaji Angka (Tanpa Titik) <button type="button" id="btnCheckWage" class="text-xs text-blue-600 hover:underline ml-2"><i class="fa-solid fa-calculator"></i> Cek Rata-rata</button></label>
                    <input type="number" id="baseSalaryInput" name="base_salary" value="{{ old('base_salary', $job->base_salary) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 3000000" required>
                    <p id="avgWageResult" class="text-xs text-slate-500 hidden mt-1"></p>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Gaji Teks (Tampil)</label>
                    <input type="text" name="salary" value="{{ old('salary', $job->salary) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Rp 3.000.000" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Kebutuhan (Orang)</label>
                    <input type="number" name="quota" value="{{ old('quota', $job->quota) }}" min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 1, 5" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Lokasi Kerja</label>
                    <input type="text" name="location" value="{{ old('location', $job->location) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Kota Surabaya, Jawa Timur" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Status Lowongan</label>
                    <div class="relative">
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium" required>
                            <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Deskripsi & Syarat Pekerjaan</label>
                <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Jelaskan detail pekerjaan, tanggung jawab, dan kualifikasi yang dibutuhkan..." required>{{ old('description', $job->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('employer.jobs.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
