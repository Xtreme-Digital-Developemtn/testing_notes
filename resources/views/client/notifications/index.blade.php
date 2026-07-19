@extends('layouts.client')
@section('title', 'الإشعارات')
@section('content')
    <h1 class="text-2xl font-bold">الإشعارات</h1>

    <form method="GET" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-4">
        <div class="flex flex-wrap items-center gap-3">
            <select name="filter" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">الكل</option>
                <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>مقروء</option>
                <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>غير مقروء</option>
            </select>
            <select name="type" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">جميع الأنواع</option>
                <option value="in_progress" {{ request('type') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="solved" {{ request('type') == 'solved' ? 'selected' : '' }}>تم الحل</option>
                <option value="reply" {{ request('type') == 'reply' ? 'selected' : '' }}>تعليق/رد</option>
            </select>
            <button type="submit" class="bg-gray-800 dark:bg-gray-700 text-white px-4 py-2.5 rounded-lg text-sm">تصفية</button>
            <a href="{{ route('client.notifications.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2.5 rounded-lg text-sm">إعادة</a>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($notifications->isEmpty())
            <p class="p-8 text-gray-400 dark:text-gray-500 text-sm text-center">لا توجد إشعارات</p>
        @else
            @foreach($notifications as $notification)
                @php $href = $notification->request ? route('client.requests.show', $notification->request_id) : '#'; @endphp
                <a href="{{ $href }}" class="block border-b border-gray-100 dark:border-gray-800 last:border-0 px-5 py-4 {{ !$notification->is_read ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }} hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            @if($notification->type === 'in_progress')
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                            @elseif($notification->type === 'solved')
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ !$notification->is_read ? 'font-semibold' : '' }}">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <span class="w-2 h-2 rounded-full bg-primary shrink-0 mt-2"></span>
                        @endif
                    </div>
                </a>
            @endforeach
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection
