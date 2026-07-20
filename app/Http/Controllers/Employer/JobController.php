<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyProfile;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('employer_id', Auth::id())->latest()->get();
        return view('dashboard.employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        if (Auth::user()->verification_status !== 'verified') {
            return redirect()->route('employer.jobs.index')->with('error', 'Akun Anda belum terverifikasi KTP. Silakan unggah KTP terlebih dahulu.');
        }

        return view('dashboard.employer.jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'salary' => 'required|string',
            'base_salary' => 'nullable|integer',
            'type' => 'required|string',
            'quota' => 'required|integer|min:1',
        ]);

        Job::create([
            'employer_id' => Auth::id(),
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'base_salary' => $request->base_salary,
            'type' => $request->type,
            'status' => 'active',
            'quota' => $request->quota
        ]);
        
        
        $profile = CompanyProfile::where('user_id', Auth::id())->first();
        if($profile) {
            $profile->xp += 100;
            if($profile->xp >= 500) {
                $profile->level = 2; 
            }
            $profile->save();
        }

        return redirect()->route('employer.jobs.index')->with('success', 'Lowongan berhasil dipublikasikan! Anda mendapatkan +100 XP!');
    }

    public function edit(Job $job)
    {
        if ($job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('dashboard.employer.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        if ($job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'salary' => 'required|string',
            'base_salary' => 'nullable|integer',
            'type' => 'required|string',
            'status' => 'required|in:active,closed',
            'quota' => 'required|integer|min:1',
        ]);

        $job->update([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'base_salary' => $request->base_salary,
            'type' => $request->type,
            'status' => $request->status,
            'quota' => $request->quota,
        ]);

        return redirect()->route('employer.jobs.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function destroy(Job $job)
    {
        if ($job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $job->delete();

        return redirect()->route('employer.jobs.index')->with('success', 'Lowongan berhasil dihapus!');
    }

    public function getAverageWage(Request $request)
    {
        $category = $request->query('category');
        if (!$category) {
            return response()->json(['average' => 0]);
        }

        $average = Job::where('category', $category)
            ->whereNotNull('base_salary')
            ->avg('base_salary');

        return response()->json([
            'category' => $category,
            'average' => $average ? round($average) : 0
        ]);
    }
}
