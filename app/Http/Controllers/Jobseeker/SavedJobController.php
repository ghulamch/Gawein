<?php
namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function index()
    {
        $savedJobs = SavedJob::with('job.employer.companyProfile')
                        ->where('user_id', Auth::id())
                        ->latest()->get();
        return view('dashboard.jobseeker.saved', compact('savedJobs'));
    }

    public function store(Request $request, \App\Models\Job $job)
    {
        $user = Auth::user();
        
        $savedJob = SavedJob::where('user_id', $user->id)
                            ->where('job_id', $job->id)
                            ->first();

        if ($savedJob) {
            $savedJob->delete();
            return back()->with('success', 'Lowongan dihapus dari daftar tersimpan.');
        } else {
            SavedJob::create([
                'user_id' => $user->id,
                'job_id' => $job->id
            ]);
            return back()->with('success', 'Lowongan berhasil disimpan.');
        }
    }
}
