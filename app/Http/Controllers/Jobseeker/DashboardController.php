<?php
namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SeekerProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $profile = SeekerProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['title' => 'Pencari Kerja', 'profile_completion' => 10]
        );

        
        $completion = 10;
        if($profile->location) $completion += 15;
        if($profile->about) $completion += 20;
        if($profile->skills) $completion += 20;
        if($profile->resume) $completion += 25;
        if($profile->avatar) $completion += 10;
        
        if ($profile->profile_completion != $completion) {
            $profile->update(['profile_completion' => $completion]);
        }

        $totalApplications = \App\Models\Application::where('user_id', Auth::id())->count();
        $savedJobsCount = \App\Models\SavedJob::where('user_id', Auth::id())->count();
        $interviewCount = \App\Models\Application::where('user_id', Auth::id())->where('status', 'interview')->count();

        
        $appliedJobIds = \App\Models\Application::where('user_id', Auth::id())->pluck('job_id')->toArray();
        $recommendedJobs = \App\Models\Job::with('employer.companyProfile')
            ->whereHas('employer')
            ->where('status', 'active')
            ->whereNotIn('id', $appliedJobIds)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('dashboard.jobseeker.index', compact('profile', 'totalApplications', 'savedJobsCount', 'interviewCount', 'recommendedJobs'));
    }
}
