<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    public function create(Request $request)
    {
        $users = User::all();
        $requests = ClientRequest::all();
        $selectedUserId = $request->get('user_id');
        $selectedRequestId = $request->get('request_id');

        return view('admin.messages.create', compact('users', 'requests', 'selectedUserId', 'selectedRequestId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'body'       => 'required|string|max:2000',
            'request_id' => 'nullable|exists:requests,id',
        ]);

        Message::create([
            'user_id'    => $data['user_id'],
            'admin_id'   => Auth::guard('admin')->id(),
            'request_id' => $data['request_id'] ?? null,
            'body'       => $data['body'],
        ]);

        return redirect()->route('admin.messages.create')
            ->with('success', 'تم إرسال الرسالة بنجاح');
    }
}
