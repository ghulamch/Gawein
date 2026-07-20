@extends('layouts.dashboard')

@section('title', 'Verifikasi Keamanan')
@section('user_name', 'Administrator')
@section('user_role', 'Super Admin')

@section('sidebar_menu')
    <li><a href="{{ route('admin') }}" class="sidebar-link"><i class="fa-solid fa-chart-pie w-5 text-center"></i> Ringkasan</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link"><i class="fa-solid fa-users w-5 text-center"></i> Kelola Pengguna</a></li>
    <li><a href="{{ route('admin.security') }}" class="sidebar-link active"><i class="fa-solid fa-shield-halved w-5 text-center"></i> Verifikasi Keamanan</a></li>
@endsection

@section('content')
<div class="mb-8">
    <h2 class="font-heading font-bold text-2xl text-slate-800 mb-2">Verifikasi Identitas Pengguna</h2>
    <p class="text-slate-500 font-medium text-sm">Tinjau dan validasi KTP yang diunggah oleh pencari kerja dan pemberi kerja baru sebelum mereka dapat menggunakan platform.</p>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-check-circle text-lg"></i> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-sm border border-amber-100"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <div class="text-sm font-bold text-slate-500 uppercase">Menunggu Tinjauan</div>
                <div class="text-3xl font-heading font-bold text-slate-800">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shadow-sm border border-emerald-100"><i class="fa-solid fa-check-double"></i></div>
            <div>
                <div class="text-sm font-bold text-slate-500 uppercase">Terverifikasi</div>
                <div class="text-3xl font-heading font-bold text-slate-800">{{ $verifiedCount }}</div>
            </div>
        </div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl shadow-sm border border-rose-100"><i class="fa-solid fa-ban"></i></div>
            <div>
                <div class="text-sm font-bold text-slate-500 uppercase">Ditolak</div>
                <div class="text-3xl font-heading font-bold text-slate-800">{{ $rejectedCount }}</div>
            </div>
        </div>
    </div>
</div>

<div class="glass-panel p-6 rounded-2xl">
    <h3 class="font-heading font-bold text-xl text-slate-800 mb-6">Daftar Antrean Verifikasi</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 text-sm font-bold text-slate-500 uppercase tracking-wider">Pengguna</th>
                    <th class="py-3 text-sm font-bold text-slate-500 uppercase tracking-wider">Peran</th>
                    <th class="py-3 text-sm font-bold text-slate-500 uppercase tracking-wider">Waktu Upload</th>
                    <th class="py-3 text-sm font-bold text-slate-500 uppercase tracking-wider">Dokumen KTP</th>
                    <th class="py-3 text-sm font-bold text-slate-500 uppercase tracking-wider text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pendingUsers as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4">
                            <div class="font-bold text-slate-800">{{ $user->name }}</div>
                            <div class="text-sm text-slate-500">{{ $user->email }}</div>
                        </td>
                        <td class="py-4">
                            @if($user->role === 'employer')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                    <i class="fa-solid fa-building"></i> Pemberi Kerja
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    <i class="fa-solid fa-user-tie"></i> Pencari Kerja
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-slate-600 font-medium text-sm">
                            {{ $user->updated_at->diffForHumans() }}
                        </td>
                        <td class="py-4">
                            <a href="{{ asset('storage/' . $user->ktp_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg text-xs font-bold transition-colors">
                                <i class="fa-solid fa-image"></i> Lihat KTP
                            </a>
                        </td>
                        <td class="py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form action="{{ route('admin.security.verify', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui verifikasi untuk {{ $user->name }}?');">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors text-xs font-bold flex items-center gap-1.5" title="Setujui">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.security.verify', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tolak verifikasi untuk {{ $user->name }}?');">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-colors text-xs font-bold flex items-center gap-1.5" title="Tolak">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="text-slate-400 mb-2"><i class="fa-solid fa-shield-check text-4xl"></i></div>
                            <div class="text-slate-500 font-bold">Semua bersih!</div>
                            <div class="text-slate-400 text-sm">Tidak ada antrean verifikasi KTP saat ini.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $pendingUsers->links() }}
    </div>
</div>
@endsection
