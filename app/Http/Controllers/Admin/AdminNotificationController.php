<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminNotification::where('admin_id', Auth::guard('admin')->id())->with(['user', 'request']);

        if ($request->get('filter') === 'read') {
            $query->where('is_read', true);
        } elseif ($request->get('filter') === 'unread') {
            $query->where('is_read', false);
        }

        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        $notifications = $query->latest()->paginate(15);

        AdminNotification::where('admin_id', Auth::guard('admin')->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.notifications.index', compact('notifications'));
    }
}
