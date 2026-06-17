@extends('layouts.client')
@section('title', $clientRequest->title)
@section('content')
    <div>
        <a href="{{ route('client.requests.index') }}" class="text-primary hover:underline text-sm">&larr; العودة للطلبات</a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-8">
        <div class="flex items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold">{{ $clientRequest->title }}</h1>
            @include('components.status-badge', ['status' => $clientRequest->status])
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <span class="text-gray-500 dark:text-gray-400 text-xs">الأولوية</span>
                <div class="mt-1.5">@include('components.priority-badge', ['priority' => $clientRequest->priority])</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <span class="text-gray-500 dark:text-gray-400 text-xs">تاريخ الرفع</span>
                <p class="mt-1.5 text-sm font-medium">{{ $clientRequest->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">الوصف</h2>
            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $clientRequest->description }}</p>
        </div>

        @if($clientRequest->image_path)
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">الصورة المرفقة</h2>
                <img src="{{ route('storage.serve', $clientRequest->mediaFiles->where('type', 'image')->first()?->id) }}" alt="صورة الطلب" class="max-w-md rounded-lg border border-gray-200 dark:border-gray-700">
            </div>
        @endif

        @if($clientRequest->video_path)
            <div>
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">الفيديو المرفق</h2>
                <video controls class="max-w-md rounded-lg border border-gray-200 dark:border-gray-700">
                    <source src="{{ route('storage.serve', $clientRequest->mediaFiles->where('type', 'video')->first()?->id) }}">
                </video>
            </div>
        @endif
    </div>

    @if($clientRequest->messages->isNotEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-8">
            <h2 class="text-lg font-bold mb-4">الرسائل</h2>
            <div class="space-y-3">
                @foreach($clientRequest->messages as $msg)
                    <div class="border-b border-gray-100 dark:border-gray-800 pb-3 last:border-0 last:pb-0">
                        <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $msg->body }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $msg->admin->name }} - {{ $msg->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
