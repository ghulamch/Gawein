<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function show(User $user)
    {
        
        if ($user->role !== 'employer') {
            abort(404, 'Profil tidak ditemukan');
        }

        $profile = $user->companyProfile;
        
        
        $jobs = Job::where('employer_id', $user->id)
                    ->where('status', 'active')
                    ->latest()
                    ->get();
        
        
        $savedJobIds = [];
        if (Auth::check() && Auth::user()->role === 'jobseeker') {
            $savedJobIds = \App\Models\SavedJob::where('user_id', Auth::id())->pluck('job_id')->toArray();
        }

        return view('company.show', compact('user', 'profile', 'jobs', 'savedJobIds'));
    }
}
