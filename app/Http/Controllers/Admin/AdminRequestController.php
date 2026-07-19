<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\ClientRequest;
use App\Models\Notification;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientRequest::with(['user', 'solver']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($year = $request->get('year')) {
            $query->whereYear('created_at', $year);
        }

        if ($month = $request->get('month')) {
            $query->whereMonth('created_at', $month);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($adminId = $request->get('admin_id')) {
            $query->where('admin_id', $adminId);
        }

        $requests = $query->latest()->paginate(15);
        $users = User::all();
        $admins = Admin::all();

        return view('admin.requests.index', compact('requests', 'users', 'admins'));
    }

    public function show($id)
    {
        $clientRequest = ClientRequest::with(['user', 'mediaFiles', 'messages.admin', 'solver', 'replies.user', 'replies.admin'])->findOrFail($id);

        return view('admin.requests.show', compact('clientRequest'));
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:not_started,in_progress,solved',
        ]);

        $clientRequest = ClientRequest::findOrFail($id);
        $oldStatus = $clientRequest->status;

        $updateData = ['status' => $data['status']];

        if ($data['status'] === 'solved' && $oldStatus !== 'solved') {
            $updateData['admin_id'] = Auth::guard('admin')->id();
        }

        if ($data['status'] !== 'solved') {
            $updateData['admin_id'] = null;
        }

        $clientRequest->update($updateData);

        if ($data['status'] === 'in_progress' && $oldStatus !== 'in_progress') {
            Notification::create([
                'user_id'    => $clientRequest->user_id,
                'request_id' => $clientRequest->id,
                'type'       => 'in_progress',
                'message'    => "بدأ العمل على طلبك: {$clientRequest->title}",
            ]);
        }

        if ($data['status'] === 'solved' && $oldStatus !== 'solved') {
            Notification::create([
                'user_id'    => $clientRequest->user_id,
                'request_id' => $clientRequest->id,
                'type'       => 'solved',
                'message'    => "تم حل طلبك: {$clientRequest->title}",
            ]);
        }

        return redirect()->route('admin.requests.show', $id)
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function reply(Request $request, $id)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $clientRequest = ClientRequest::findOrFail($id);

        Reply::create([
            'request_id' => $clientRequest->id,
            'admin_id'   => Auth::guard('admin')->id(),
            'body'       => $data['body'],
        ]);

        Notification::create([
            'user_id'    => $clientRequest->user_id,
            'request_id' => $clientRequest->id,
            'type'       => 'reply',
            'message'    => "ردّ المحاسب على طلبك: {$clientRequest->title}",
        ]);

        return redirect()->route('admin.requests.show', $id)
            ->with('success', 'تم إضافة الرد بنجاح');
    }
}
