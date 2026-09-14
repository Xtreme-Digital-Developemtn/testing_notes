<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\ClientRequest;
use App\Models\MediaFile;
use App\Models\Notification;
use App\Models\Reply;
use App\Notifications\AdminRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientRequest::where('user_id', Auth::id());

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

        $requests = $query->latest()->paginate(10);

        return view('client.requests.index', compact('requests'));
    }

    public function create()
    {
        return view('client.requests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'video'       => 'nullable|mimes:mp4,mov|max:65536',
            'document'    => 'nullable|mimes:pdf|max:20480',
        ]);

        $clientRequest = ClientRequest::create([
            'user_id'     => Auth::id(),
            'title'       => $data['title'],
            'description' => $data['description'],
            'priority'    => $data['priority'],
            'status'      => 'not_started',
        ]);

        $admins = Admin::all();
        $message = "عميل جديد (" . Auth::user()->name . ") أرسل طلب جديد: {$clientRequest->title}";

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id'   => $admin->id,
                'user_id'    => Auth::id(),
                'request_id' => $clientRequest->id,
                'message'    => $message,
            ]);

            $admin->notify(new AdminRequestNotification($clientRequest, $message));
        }

        $userId = Auth::id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store("requests/{$userId}/{$clientRequest->id}", 'local');
            $clientRequest->update(['image_path' => $path]);

            MediaFile::create([
                'user_id'       => $userId,
                'request_id'    => $clientRequest->id,
                'type'          => 'image',
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'size'          => $file->getSize(),
            ]);
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $path = $file->store("requests/{$userId}/{$clientRequest->id}", 'local');
            $clientRequest->update(['video_path' => $path]);

            MediaFile::create([
                'user_id'       => $userId,
                'request_id'    => $clientRequest->id,
                'type'          => 'video',
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'size'          => $file->getSize(),
            ]);
        }

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store("requests/{$userId}/{$clientRequest->id}", 'local');

            MediaFile::create([
                'user_id'       => $userId,
                'request_id'    => $clientRequest->id,
                'type'          => 'document',
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'size'          => $file->getSize(),
            ]);
        }

        return redirect()->route('client.requests.index')
            ->with('success', 'تم رفع الطلب بنجاح');
    }

    public function show($id)
    {
        $clientRequest = ClientRequest::where('user_id', Auth::id())
            ->with(['mediaFiles', 'messages.admin', 'replies.user', 'replies.admin'])
            ->findOrFail($id);

        return view('client.requests.show', compact('clientRequest'));
    }

    public function reply(Request $request, $id)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $clientRequest = ClientRequest::where('user_id', Auth::id())->findOrFail($id);

        Reply::create([
            'request_id' => $clientRequest->id,
            'user_id'    => Auth::id(),
            'body'       => $data['body'],
        ]);

        $admins = Admin::all();
        $message = "علّق العميل " . Auth::user()->name . " على طلب: {$clientRequest->title}";

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id'   => $admin->id,
                'user_id'    => Auth::id(),
                'request_id' => $clientRequest->id,
                'message'    => $message,
            ]);

            $admin->notify(new AdminRequestNotification($clientRequest, $message));
        }

        return redirect()->route('client.requests.show', $id)
            ->with('success', 'تم إضافة تعليقك بنجاح');
    }
}
