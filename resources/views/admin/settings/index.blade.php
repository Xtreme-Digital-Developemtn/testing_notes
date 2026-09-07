@extends('layouts.admin')
@section('title', 'إعدادات لوحة التحكم')
@section('content')
    <h1 class="text-2xl font-bold mb-6">إعدادات لوحة التحكم</h1>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
        @csrf

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 space-y-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اللوغو الحالي</label>
                <div class="flex items-center gap-4">
                    @if(!empty($settings['logo_path']))
                        <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo" class="h-16 w-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    @else
                        <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 text-2xl font-bold">A</div>
                    @endif
                </div>
            </div>

            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تغيير اللوجو</label>
                <input type="file" name="logo" id="logo" accept="image/*"
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-admin-50 file:text-admin-700 hover:file:bg-admin-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, SVG - الحد الأقصى 2MB</p>
            </div>

            <div>
                <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم الموقع</label>
                <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? '' }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm focus:border-admin-500 focus:ring-1 focus:ring-admin-500 dark:text-white"
                    placeholder="اسم الموقع">
            </div>

        </div>

        <div class="mt-6">
            <button type="submit"
                class="px-6 py-2.5 bg-admin-600 text-white rounded-lg hover:bg-admin-700 text-sm font-semibold transition">
                حفظ الإعدادات
            </button>
        </div>
    </form>
@endsection
