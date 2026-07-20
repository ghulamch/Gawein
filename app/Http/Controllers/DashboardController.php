<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalUsers = \App\Models\User::count();
        $activeJobs = \App\Models\Job::where('status', 'active')->count();
        $totalTransactions = \App\Models\Transaction::where('status', 'success')->sum('amount');
        $recentActivities = \App\Models\Activity::latest()->limit(5)->get();

        
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = \App\Models\User::whereDate('created_at', $date)->count();
            $chartData[] = [
                'date' => now()->subDays($i)->format('d M'),
                'count' => $count
            ];
        }

        return view('dashboard.admin', compact('totalUsers', 'activeJobs', 'totalTransactions', 'recentActivities', 'chartData'));
    }
}
