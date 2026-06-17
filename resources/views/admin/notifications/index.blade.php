@extends('layouts.admin')
@section('title', 'إشعارات الأدمن')
@section('content')
    <h1 class="text-2xl font-bold">إشعارات النظام</h1>

    <form method="GET" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-4">
        <div class="flex flex-wrap items-center gap-3">
            <select name="filter" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">الكل</option>
                <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>غير مقروء</option>
                <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>مقروء</option>
            </select>
            <select name="user_id" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">كل العملاء</option>
                @php $users = \App\Models\User::all(); @endphp
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 dark:bg-gray-700 text-white px-4 py-2.5 rounded-lg text-sm">تصفية</button>
            <a href="{{ route('admin.notifications.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2.5 rounded-lg text-sm">إعادة</a>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($notifications->isEmpty())
            <p class="p-8 text-gray-400 dark:text-gray-500 text-sm text-center">لا توجد إشعارات</p>
        @else
            @foreach($notifications as $notification)
                <div class="border-b border-gray-100 dark:border-gray-800 last:border-0 px-5 py-4 {{ !$notification->is_read ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 w-8 h-8 rounded-full bg-primary/20 dark:bg-primary/30 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ !$notification->is_read ? 'font-semibold' : '' }}">{{ $notification->message }}</p>
                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 flex flex-wrap items-center gap-3">
                                <span>العميل: {{ $notification->user->name }}</span>
                                <span>{{ $notification->created_at->format('Y-m-d H:i') }}</span>
                                @if($notification->request)
                                    <a href="{{ route('admin.requests.show', $notification->request_id) }}" class="text-primary hover:underline">عرض الطلب</a>
                                @endif
                            </div>
                        </div>
                        @if(!$notification->is_read)
                            <span class="w-2 h-2 rounded-full bg-primary shrink-0 mt-2"></span>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection
