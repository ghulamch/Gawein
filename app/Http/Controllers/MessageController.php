<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        
        
        
        

        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        $conversations = [];
        $seenUsers = [];

        foreach ($messages as $msg) {
            $otherUser = $msg->sender_id == $userId ? $msg->receiver : $msg->sender;
            if (!$otherUser) continue;

            if (!in_array($otherUser->id, $seenUsers)) {
                $seenUsers[] = $otherUser->id;
                $conversations[] = [
                    'user' => $otherUser,
                    'latest_message' => $msg,
                    'unread_count' => Message::where('sender_id', $otherUser->id)
                                             ->where('receiver_id', $userId)
                                             ->where('is_read', false)
                                             ->count()
                ];
            }
        }

        return view('dashboard.messages.index', compact('conversations'));
    }

    public function show(Request $request, $id)
    {
        $userId = Auth::id();
        $otherUser = User::findOrFail($id);

        
        
        
        
        $messageCount = Message::where(function($q) use ($userId, $id) {
            $q->where('sender_id', $userId)->where('receiver_id', $id);
        })->orWhere(function($q) use ($userId, $id) {
            $q->where('sender_id', $id)->where('receiver_id', $userId);
        })->count();

        if ($messageCount == 0 && Auth::user()->role === 'jobseeker') {
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Hanya Pemberi Kerja yang dapat memulai percakapan.'], 403);
            }
            return redirect()->route('jobseeker.dashboard')->with('error', 'Anda tidak dapat memulai percakapan dengan Pemberi Kerja.');
        }

        
        Message::where('sender_id', $id)
               ->where('receiver_id', $userId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($userId, $id) {
            $q->where('sender_id', $userId)->where('receiver_id', $id);
        })->orWhere(function($q) use ($userId, $id) {
            $q->where('sender_id', $id)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        if ($request->ajax()) {
            
            $formatted = $messages->map(function($msg) use ($userId) {
                return [
                    'id' => $msg->id,
                    'is_mine' => $msg->sender_id == $userId,
                    'content' => $msg->content,
                    'time' => $msg->created_at->format('H:i'),
                    'date' => $msg->created_at->format('Y-m-d'),
                    'date_human' => $msg->created_at->isToday() ? 'Hari Ini' : ($msg->created_at->isYesterday() ? 'Kemarin' : $msg->created_at->format('d M Y'))
                ];
            });
            return response()->json($formatted);
        }

        return view('dashboard.messages.show', compact('otherUser', 'messages'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $userId = Auth::id();

        
        $messageCount = Message::where(function($q) use ($userId, $id) {
            $q->where('sender_id', $userId)->where('receiver_id', $id);
        })->orWhere(function($q) use ($userId, $id) {
            $q->where('sender_id', $id)->where('receiver_id', $userId);
        })->count();

        if ($messageCount == 0 && Auth::user()->role === 'jobseeker') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Hanya Pemberi Kerja yang dapat memulai percakapan.'], 403);
            }
            return back()->with('error', 'Hanya Pemberi Kerja yang dapat memulai percakapan.');
        }

        $message = Message::create([
            'sender_id' => $userId,
            'receiver_id' => $id,
            'content' => $request->content,
            'is_read' => false
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'is_mine' => true,
                    'content' => $message->content,
                    'time' => $message->created_at->format('H:i'),
                    'date' => $message->created_at->format('Y-m-d'),
                    'date_human' => 'Hari Ini'
                ]
            ]);
        }

        return back();
    }
}
