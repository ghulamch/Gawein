<?php
namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('employer.companyProfile')->where('status', 'active');
        
        $profile = Auth::user()->seekerProfile;
        $domicile = $request->input('location', $profile ? $profile->location : null);
        
        if ($domicile) {
            $query->where('location', 'like', '%' . $domicile . '%');
        }
        
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $jobs = $query->latest()->get();

        $savedJobIds = \App\Models\SavedJob::where('user_id', Auth::id())->pluck('job_id')->toArray();
        
        
        $popularFilters = \App\Models\Job::where('status', 'active')
            ->select('type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('type');

        return view('dashboard.jobseeker.jobs', compact('jobs', 'search', 'domicile', 'savedJobIds', 'popularFilters'));
    }

    public function apply(Request $request, Job $job)
    {
        $user = Auth::user();

        if ($user->verification_status !== 'verified') {
            return back()->with('error', 'Akun Anda belum terverifikasi KTP. Silakan unggah KTP terlebih dahulu.');
        }

        
        $existingApplication = \App\Models\Application::where('user_id', $user->id)
                                                      ->where('job_id', $job->id)
                                                      ->first();
        if ($existingApplication) {
            return back()->with('error', 'Anda sudah melamar pekerjaan ini.');
        }

        \App\Models\Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'cover_letter' => $request->input('cover_letter', ''),
            'status' => 'pending'
        ]);

        
        $profile = $user->seekerProfile;
        if($profile) {
            $profile->xp += 50; 
            if($profile->xp >= 500) {
                $profile->level = 2; 
            }
            $profile->save();
        }

        return back()->with('success', 'Berhasil melamar pekerjaan! Anda mendapatkan +50 XP!');
    }
}
