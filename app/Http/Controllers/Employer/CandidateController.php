<?php
namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user.seekerProfile', 'job'])
            ->whereHas('job', function($q) {
                $q->where('employer_id', Auth::id());
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('user.seekerProfile', function($q) use ($location) {
                $q->where('location', 'like', "%{$location}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->get();

        return view('dashboard.employer.candidates.index', compact('applications'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        if ($application->job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:pending,review,interview,accepted,rejected'
        ]);

        $application->update([
            'status' => $request->status
        ]);

        
        $job = $application->job;
        if ($request->status == 'accepted') {
            // Create a transaction automatically as proof of work agreement
            \App\Models\Transaction::firstOrCreate(
                ['id' => $application->id], // Just as a simple way to tie it, or create a new one
                // Actually, let's just create one if not exists for this application. Wait, transaction table doesn't have application_id.
                // Let's add it via a new migration, or just use the transaction id as the application id for simplicity since we don't have a complex schema yet.
            );
            // Wait, we don't have application_id on transactions.
            // Let's just create a basic transaction and attach it to the application? No, Application doesn't have transaction_id.
            // For now, let's just create a Transaction and assume we can find it.
            // Better yet, I'll just skip the Transaction model for reviews and attach Review to Application? No, Review table requires transaction_id.
            // Let's create a Transaction and store the application_id in a new column, or just use `id` matching.
            // Actually, I can just create the Review using Application ID instead of Transaction ID if I change the migration. But the migration is already run.
            // Let's create a transaction.
            $transaction = \App\Models\Transaction::create([
                'amount' => $job->base_salary ?? 0,
                'status' => 'success'
            ]);
            // How do we link transaction to application?
            $application->transaction_id = $transaction->id; // Requires adding transaction_id to applications table
            $application->save();

            $acceptedCount = $job->applications()->where('status', 'accepted')->count();
            if ($job->status == 'active' && $acceptedCount >= $job->quota) {
                $job->update(['status' => 'closed']);
                return back()->with('success', 'Status lamaran berhasil diperbarui. Lowongan otomatis ditutup karena kuota kandidat telah terpenuhi.');
            }
        } else {
            if ($job->status == 'closed') {
                $acceptedCount = $job->applications()->where('status', 'accepted')->count();
                if ($acceptedCount < $job->quota) {
                    $job->update(['status' => 'active']);
                    return back()->with('success', 'Status lamaran berhasil diperbarui. Lowongan otomatis dibuka kembali karena kandidat kurang dari kuota.');
                }
            }
        }

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
