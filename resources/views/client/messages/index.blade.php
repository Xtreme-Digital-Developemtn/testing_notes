@extends('layouts.client')
@section('title', 'الرسائل')
@section('content')
    <h1 class="text-2xl font-bold">الرسائل الواردة</h1>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($messages->isEmpty())
            <p class="p-8 text-gray-400 dark:text-gray-500 text-sm text-center">لا توجد رسائل</p>
        @else
            @foreach($messages as $message)
                <div class="border-b border-gray-100 dark:border-gray-800 last:border-0 px-5 py-4 {{ !$message->is_read ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $message->body }}</p>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $message->admin->name }}
                        </span>
                        <span>{{ $message->created_at->format('Y-m-d H:i') }}</span>
                        @if($message->request)
                            <a href="{{ route('client.requests.show', $message->request_id) }}" class="text-primary hover:underline">الطلب: {{ $message->request->title }}</a>
                        @endif
                    </div>
                    @if(!$message->is_read)
                        <form method="POST" action="{{ route('client.messages.read', $message->id) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-xs text-primary hover:underline">تحديد كمقروء</button>
                        </form>
                    @endif
                </div>
            @endforeach
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">{{ $messages->links() }}</div>
        @endif
    </div>
@endsection
