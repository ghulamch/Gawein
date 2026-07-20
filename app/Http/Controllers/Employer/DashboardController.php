<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        
        $profile = CompanyProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name, 'level' => 1, 'xp' => 0]
        );

        $activeJobsCount = Job::where('employer_id', $user->id)->where('status', 'active')->count();
        $totalCandidates = 0; 

        return view('dashboard.employer', compact('profile', 'activeJobsCount', 'totalCandidates'));
    }
}
