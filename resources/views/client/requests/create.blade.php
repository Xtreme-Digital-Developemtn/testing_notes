@extends('layouts.client')
@section('title', 'طلب جديد')
@section('content')
    <div>
        <a href="{{ route('client.requests.index') }}" class="text-primary hover:underline text-sm">&larr; العودة للطلبات</a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-8 max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">طلب جديد</h1>

        <form method="POST" action="{{ route('client.requests.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">العنوان</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">الوصف</label>
                <textarea name="description" rows="4" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">الأولوية</label>
                <select name="priority" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary @error('priority') border-red-500 @enderror">
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                </select>
                @error('priority')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">صورة (اختياري - jpg, png - حد أقصى 5MB)</label>
                <input type="file" name="image" accept="image/jpg,image/jpeg,image/png" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm file:ml-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white file:text-sm file:cursor-pointer @error('image') border-red-500 @enderror">
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">فيديو (اختياري - mp4, mov - حد أقصى 50MB)</label>
                <input type="file" name="video" accept="video/mp4,video/quicktime" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm file:ml-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white file:text-sm file:cursor-pointer @error('video') border-red-500 @enderror">
                @error('video')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">مستند PDF (اختياري - حد أقصى 20MB)</label>
                <input type="file" name="document" accept="application/pdf" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm file:ml-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white file:text-sm file:cursor-pointer @error('document') border-red-500 @enderror">
                @error('document')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors">رفع الطلب</button>
            </div>
        </form>
    </div>
@endsection
