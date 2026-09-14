@extends('layouts.client')
@section('title', $clientRequest->title)
@section('content')
    <div>
        <a href="{{ route('client.requests.index') }}" class="text-primary hover:underline text-sm">&larr; العودة للطلبات</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg text-sm border border-green-200 dark:border-green-800">{{ session('success') }}</div>
    @endif

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
            @php $imageFile = $clientRequest->mediaFiles->where('type', 'image')->first(); @endphp
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">الصورة المرفقة</h2>
                <img src="{{ route('storage.inline', $imageFile?->id) }}" alt="صورة الطلب" class="max-w-md rounded-lg border border-gray-200 dark:border-gray-700">
                <a href="{{ route('storage.serve', $imageFile?->id) }}" class="inline-flex items-center gap-2 mt-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    تحميل الصورة
                </a>
            </div>
        @endif

        @if($clientRequest->video_path)
            @php $videoFile = $clientRequest->mediaFiles->where('type', 'video')->first(); @endphp
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">الفيديو المرفق</h2>
                <video controls class="max-w-md rounded-lg border border-gray-200 dark:border-gray-700" preload="metadata">
                    <source src="{{ route('storage.inline', $videoFile?->id) }}" type="{{ $videoFile?->mimeType ?? 'video/mp4' }}">
                    المتصفح لا يدعم عرض الفيديو
                </video>
                <a href="{{ route('storage.serve', $videoFile?->id) }}" class="inline-flex items-center gap-2 mt-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    تحميل الفيديو
                </a>
            </div>
        @endif

        @php $documentFiles = $clientRequest->mediaFiles->where('type', 'document'); @endphp
        @if($documentFiles->isNotEmpty())
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">الملفات المرفقة (PDF)</h2>
                <div class="space-y-3">
                    @foreach($documentFiles as $doc)
                        <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $doc->original_name }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ round($doc->size / 1024, 1) }} KB</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('storage.inline', $doc->id) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    عرض
                                </a>
                                <a href="{{ route('storage.serve', $doc->id) }}" class="inline-flex items-center gap-1.5 bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-primary/90 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    تحميل
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
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

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-8">
        <h2 class="text-lg font-bold mb-4">التعليقات والردود</h2>

        @if($clientRequest->replies->isEmpty())
            <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">لا توجد تعليقات بعد</p>
        @else
            <div class="space-y-4 mb-6">
                @foreach($clientRequest->replies as $reply)
                    @php $isAdmin = $reply->admin_id !== null; @endphp
                    <div class="flex gap-3 {{ $isAdmin ? 'flex-row-reverse' : '' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $isAdmin ? 'bg-admin-500 text-white' : 'bg-primary text-white' }}">
                            {{ $isAdmin ? 'م' : 'ع' }}
                        </div>
                        <div class="flex-1 {{ $isAdmin ? 'text-right' : '' }}">
                            <div class="inline-block bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-3 max-w-lg {{ $isAdmin ? 'rounded-tr-sm' : 'rounded-tl-sm' }}">
                                <p class="text-xs font-semibold mb-1 {{ $isAdmin ? 'text-admin-500' : 'text-primary' }}">
                                    {{ $isAdmin ? $reply->admin->name : 'أنت' }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $reply->body }}</p>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 {{ $isAdmin ? 'text-right' : '' }}">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('client.requests.reply', $clientRequest->id) }}" class="border-t border-gray-200 dark:border-gray-700 pt-4">
            @csrf
            <textarea name="body" rows="2" placeholder="اكتب تعليقك هنا..." required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-transparent resize-none"></textarea>
            @error('body')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <div class="flex justify-end mt-3">
                <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">إرسال التعليق</button>
            </div>
        </form>
    </div>
@endsection
