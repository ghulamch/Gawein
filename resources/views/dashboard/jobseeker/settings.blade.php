@extends('layouts.dashboard')

@section('title', 'Pengaturan Akun')
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
    <li><a href="{{ route('jobseeker.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-user w-5 text-center"></i> Profil Saya</a></li>
    <li><a href="{{ route('jobseeker.settings.index') }}" class="sidebar-link active"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto mb-10">
    <div class="glass-panel p-8 md:p-10 rounded-[2rem] shadow-sm">
        
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h2 class="text-2xl font-heading font-black text-slate-800">Keamanan Akun</h2>
                <p class="text-sm text-slate-500 font-medium">Atur kata sandi dan email untuk keamanan akun lamaran kerjamu.</p>
            </div>
        </div>
        
        <div class="mt-10 space-y-8">
            
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="font-bold text-slate-800 mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-slate-400"></i> Email Saat Ini
                    </div>
                    <div class="text-sm text-slate-500 mb-3">Email ini digunakan HRD untuk menghubungimu (Tidak dapat diubah).</div>
                    <input type="email" value="{{ Auth::user()->email ?? 'email@example.com' }}" class="px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-100/50 text-slate-500 outline-none w-full max-w-md font-medium cursor-not-allowed" disabled>
                </div>
            </div>
            
            
            <form action="{{ route('jobseeker.settings.password') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-10 opacity-50"></div>
                
                @csrf
                @method('PUT')
                
                <div class="font-bold text-slate-800 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-key text-blue-500"></i> Perbarui Kata Sandi
                </div>
                <div class="text-sm text-slate-500 mb-6">Pastikan akunmu aman dengan kata sandi yang kuat (minimal 8 karakter).</div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">Kata Sandi Baru</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password" placeholder="••••••••" required class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror">
                        </div>
                        @error('password')
                        <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <i class="fa-solid fa-check-double absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-slate-800 text-white hover:bg-slate-900 shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        Simpan Sandi <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>

            
            <div class="mt-12 pt-8 border-t border-slate-100">
                <div class="flex items-center gap-2 text-rose-600 font-black text-lg mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i> Zona Berbahaya
                </div>
                
                <div class="p-6 border border-rose-200 bg-rose-50/50 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <div class="font-bold text-slate-800 mb-1">Hapus Akun Permanen</div>
                        <div class="text-sm text-slate-600 max-w-md leading-relaxed">Tindakan ini tidak dapat dibatalkan. Semua data profil, resume, serta riwayat lamaran Anda akan dihapus secara permanen dari sistem kami.</div>
                    </div>
                    
                    <form action="{{ route('jobseeker.settings.destroy') }}" method="POST" id="form-delete-account" class="w-full md:w-auto shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()" class="w-full md:w-auto px-6 py-3 rounded-xl font-bold text-sm bg-white border-2 border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 shadow-sm transition-all whitespace-nowrap flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash-can"></i> Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#2563eb',
        customClass: {
            title: 'font-heading text-xl text-slate-800 font-black',
            confirmButton: 'px-6 py-2.5 rounded-xl font-bold bg-blue-600 hover:bg-blue-700 shadow-sm'
        }
    });
    @endif

    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Akun Permanen?',
            html: "<p class='text-slate-500 font-medium text-sm mt-2'>Tindakan ini <b>tidak dapat dibatalkan</b>. Semua data Anda akan hilang selamanya.</p>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus Akun',
            cancelButtonText: 'Batal',
            customClass: {
                title: 'font-heading text-xl text-slate-800 font-black',
                confirmButton: 'px-6 py-3 rounded-xl font-bold bg-rose-600 hover:bg-rose-700 text-white mx-2 shadow-sm',
                cancelButton: 'px-6 py-3 rounded-xl font-bold bg-slate-500 hover:bg-slate-600 text-white mx-2 shadow-sm',
                popup: 'rounded-[2rem] border border-slate-100'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-account').submit();
            }
        });
    }
</script>
@endsection
