<?php
namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('job.employer.companyProfile')
                        ->where('user_id', Auth::id())
                        ->latest()->get();
        return view('dashboard.jobseeker.applications', compact('applications'));
    }
}
