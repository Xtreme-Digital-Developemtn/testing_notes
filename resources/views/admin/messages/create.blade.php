@extends('layouts.admin')
@section('title', 'إرسال رسالة')
@section('content')
    <h1 class="text-2xl font-bold">إرسال رسالة للعميل</h1>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-8 max-w-2xl">
        <form method="POST" action="{{ route('admin.messages.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">العميل</label>
                <select name="user_id" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent @error('user_id') border-red-500 @enderror">
                    <option value="">اختر العميل</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $selectedUserId) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">الطلب المرتبط (اختياري)</label>
                <select name="request_id" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary">
                    <option value="">بدون طلب</option>
                    @foreach($requests as $req)
                        <option value="{{ $req->id }}" {{ old('request_id', $selectedRequestId) == $req->id ? 'selected' : '' }}>{{ $req->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 mb-1.5 text-sm font-medium">نص الرسالة</label>
                <textarea name="body" rows="5" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent @error('body') border-red-500 @enderror">{{ old('body') }}</textarea>
                @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">إرسال</button>
            </div>
        </form>
    </div>
@endsection
