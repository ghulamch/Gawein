<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // Determine reviewer and reviewee based on transaction and job context
        // In this simple setup, we might need to know if the Auth::user() is employer or jobseeker.
        // Let's assume transaction has job application info, or we just pass reviewee_id.
        $request->validate([
            'reviewee_id' => 'required|exists:users,id'
        ]);

        // Check if already reviewed
        $existing = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk transaksi ini.');
        }

        Review::create([
            'transaction_id' => $transaction->id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $request->reviewee_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Ulasan berhasil disimpan!');
    }
}
