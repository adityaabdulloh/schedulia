<?php

namespace App\Http\Controllers;

use App\Events\PusherTestEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PusherController extends Controller
{
    /**
     * Show the pusher test view.
     */
    public function index()
    {
        return view('pusher-test');
    }

    /**
     * Broadcast a message using Pusher.
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate(['message' => 'required|string|max:255']);

            broadcast(new PusherTestEvent($request->message));

            return response()->json(['status' => 'Message Sent!']);
        } catch (\Exception $e) {
            Log::error('Pusher sendMessage error: ' . $e->getMessage());
            return response()->json(['status' => 'Error sending message', 'error' => $e->getMessage()], 500);
        }
    }
}
