<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientNotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id());

        if ($request->get('filter') === 'read') {
            $query->where('is_read', true);
        } elseif ($request->get('filter') === 'unread') {
            $query->where('is_read', false);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $notifications = $query->latest()->paginate(15);

        Notification::where('user_id', Auth::id())->where('is_read', false)
            ->update(['is_read' => true]);

        return view('client.notifications.index', compact('notifications'));
    }
}
