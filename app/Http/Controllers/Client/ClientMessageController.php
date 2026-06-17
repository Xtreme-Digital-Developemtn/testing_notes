<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientMessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('user_id', Auth::id())
            ->with(['admin', 'request'])
            ->latest()
            ->paginate(15);

        return view('client.messages.index', compact('messages'));
    }

    public function markAsRead($id)
    {
        $message = Message::where('user_id', Auth::id())->findOrFail($id);
        $message->update(['is_read' => true]);

        return back();
    }
}
