@extends('layouts.client')
@section('title', 'الملفات')
@section('content')
    <h1 class="text-2xl font-bold">مكتبة الملفات</h1>

    <form method="GET" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-4">
        <div class="flex flex-wrap items-center gap-3">
            <select name="type" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">الكل</option>
                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>صور</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>فيديوهات</option>
                <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>مستندات PDF</option>
            </select>
            <select name="request_id" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">كل الطلبات</option>
                @foreach($requests as $req)
                    <option value="{{ $req->id }}" {{ request('request_id') == $req->id ? 'selected' : '' }}>{{ $req->title }}</option>
                @endforeach
            </select>
            <select name="year" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">كل السنوات</option>
                @foreach(range(date('Y'), 2020) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select name="month" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">كل الشهور</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 dark:bg-gray-700 text-white px-4 py-2.5 rounded-lg text-sm">تصفية</button>
            <a href="{{ route('client.storage.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2.5 rounded-lg text-sm">إعادة</a>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($files as $file)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden group hover:shadow-md transition-all duration-200">
                @if($file->type === 'image')
                    <img src="{{ route('storage.inline', $file->id) }}" alt="{{ $file->original_name }}" class="w-full h-40 object-cover">
                @elseif($file->type === 'document')
                    <div class="w-full h-40 bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                @else
                    <div class="w-full h-40 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <div class="p-3.5">
                    <p class="text-sm truncate text-gray-700 dark:text-gray-300">{{ $file->original_name }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ number_format($file->size / 1024, 1) }} KB</p>
                    <div class="flex items-center gap-2 mt-2">
                        @if($file->type === 'document')
                            <a href="{{ route('storage.inline', $file->id) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-500 text-xs hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                عرض
                            </a>
                        @endif
                        <a href="{{ route('storage.serve', $file->id) }}" class="inline-flex items-center gap-1 text-primary text-xs hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            تحميل
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 dark:text-gray-500 py-20">لا توجد ملفات</div>
        @endforelse
    </div>

    @if($files->hasPages())
        <div>{{ $files->links() }}</div>
    @endif
@endsection
