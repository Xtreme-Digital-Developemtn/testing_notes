@extends('layouts.admin')
@section('title', 'لوحة التحكم')
@section('content')
    <h1 class="text-2xl font-bold">لوحة تحكم الأدمن</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 text-center">
            <div class="text-3xl font-bold text-admin-500">{{ $totalRequests }}</div>
            <div class="text-gray-500 dark:text-gray-400 mt-2 text-sm">إجمالي الطلبات</div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 text-center">
            <div class="text-3xl font-bold text-gray-400">{{ $notStarted }}</div>
            <div class="text-gray-500 dark:text-gray-400 mt-2 text-sm">لم تبدأ</div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 text-center">
            <div class="text-3xl font-bold text-yellow-500">{{ $inProgress }}</div>
            <div class="text-gray-500 dark:text-gray-400 mt-2 text-sm">قيد التنفيذ</div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 text-center">
            <div class="text-3xl font-bold text-green-500">{{ $solved }}</div>
            <div class="text-gray-500 dark:text-gray-400 mt-2 text-sm">تم الحل</div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 text-center">
            <div class="text-3xl font-bold text-indigo-500">{{ $totalClients }}</div>
            <div class="text-gray-500 dark:text-gray-400 mt-2 text-sm">العملاء</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-bold mb-4">الطلبات حسب الحالة</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                        <span class="text-sm">لم تبدأ</span>
                    </div>
                    <span class="font-bold">{{ $notStarted }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="text-sm">قيد التنفيذ</span>
                    </div>
                    <span class="font-bold">{{ $inProgress }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                        <span class="text-sm">تم الحل</span>
                    </div>
                    <span class="font-bold">{{ $solved }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-bold mb-4">الطلبات حسب الأولوية</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                        <span class="text-sm">منخفضة</span>
                    </div>
                    <span class="font-bold">{{ $priorityDistribution['low'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="text-sm">متوسطة</span>
                    </div>
                    <span class="font-bold">{{ $priorityDistribution['medium'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="text-sm">عالية</span>
                    </div>
                    <span class="font-bold">{{ $priorityDistribution['high'] }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
