@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('user_name', 'Administrator')
@section('user_role', 'Super Admin')

@section('sidebar_menu')
    <li><a href="{{ route('admin') }}" class="sidebar-link {{ request()->routeIs('admin') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie w-5 text-center"></i> Ringkasan</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="fa-solid fa-users w-5 text-center"></i> Kelola Pengguna</a></li>
    <li><a href="{{ route('admin.security') }}" class="sidebar-link {{ request()->routeIs('admin.security') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved w-5 text-center"></i> Verifikasi Keamanan</a></li>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Pengguna</div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="text-4xl font-heading font-bold text-slate-800 mb-2">{{ number_format($totalUsers) }}</div>
        <div class="text-sm font-medium text-emerald-600 flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> +12.5% bulan ini</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Lowongan Aktif</div>
            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-briefcase"></i></div>
        </div>
        <div class="text-4xl font-heading font-bold text-slate-800 mb-2">{{ number_format($activeJobs) }}</div>
        <div class="text-sm font-medium text-emerald-600 flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> +5.2% bulan ini</div>
    </div>
    
    <div class="glass-panel p-6 rounded-2xl card-hover cursor-pointer">
        <div class="flex justify-between items-center mb-4">
            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Transaksi Sukses</div>
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg shadow-sm"><i class="fa-solid fa-wallet"></i></div>
        </div>
        <div class="text-4xl font-heading font-bold text-slate-800 mb-2">Rp {{ number_format($totalTransactions / 1000000, 1) }}M</div>
        <div class="text-sm font-medium text-rose-500 flex items-center gap-1"><i class="fa-solid fa-arrow-trend-down"></i> -1.5% bulan ini</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 glass-panel p-6 rounded-2xl flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-heading font-bold text-xl text-slate-800">Pertumbuhan Pengguna</h3>
            <select class="text-sm font-semibold text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option>7 Hari Terakhir</option>
                <option>Bulan Ini</option>
                <option>Tahun Ini</option>
            </select>
        </div>
        
        
        <div class="flex-1 w-full bg-slate-50 border border-slate-100 rounded-xl px-6 pt-4 pb-4 relative overflow-hidden group">
            <canvas id="userGrowthChart" height="250"></canvas>
        </div>
    </div>

    
    <div class="glass-panel p-6 rounded-2xl flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-heading font-bold text-xl text-slate-800">Aktivitas Terbaru</h3>
            <button class="text-blue-600 text-sm font-semibold hover:text-blue-800 transition-colors">Semua</button>
        </div>
        <div class="flex-1 overflow-y-auto pr-2">
            <div class="flex flex-col gap-4">
                @forelse ($recentActivities as $activity)
                    <div class="flex items-start gap-4 pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="w-2 h-2 mt-2 rounded-full @if($activity->status === 'Selesai' || $activity->status === 'success') bg-emerald-500 @else bg-amber-500 @endif shadow-sm"></div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-800">{{ $activity->action }}</div>
                            <div class="text-xs text-slate-500 font-medium">{{ $activity->user_name }}</div>
                        </div>
                        <div class="text-[10px] font-bold text-slate-400 whitespace-nowrap">{{ $activity->created_at->diffForHumans(null, true, true) }}</div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-sm font-medium">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    

    const rawData = @json($chartData);
    const labels = rawData.map(item => item.date);
    const data = rawData.map(item => item.count);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pengguna Baru',
                data: data,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                hoverBackgroundColor: 'rgba(37, 99, 235, 1)',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#64748b' },
                    grid: { color: '#e2e8f0', drawBorder: false }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { display: false, drawBorder: false }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                    bodyFont: { size: 14, weight: 'bold', family: "'Outfit', sans-serif" },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '+' + context.parsed.y + ' Pengguna';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
