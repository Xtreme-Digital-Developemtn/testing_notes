@extends('layouts.client')
@section('title', 'لوحة التحكم')
@section('content')
    <h1 class="text-2xl font-bold">لوحة التحكم</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 text-center">
            <div class="text-3xl font-bold text-primary">{{ $totalRequests }}</div>
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
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
        <h2 class="text-lg font-bold mb-4">آخر 5 طلبات</h2>
        @if($latestRequests->isEmpty())
            <p class="text-gray-400 dark:text-gray-500 text-sm">لا توجد طلبات بعد</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-3 px-4 text-gray-500 dark:text-gray-400 font-medium">العنوان</th>
                            <th class="pb-3 px-4 text-gray-500 dark:text-gray-400 font-medium">الأولوية</th>
                            <th class="pb-3 px-4 text-gray-500 dark:text-gray-400 font-medium">الحالة</th>
                            <th class="pb-3 px-4 text-gray-500 dark:text-gray-400 font-medium">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestRequests as $req)
                            <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="py-3 px-4">
                                    <a href="{{ route('client.requests.show', $req->id) }}" class="text-primary hover:underline font-medium">{{ $req->title }}</a>
                                </td>
                                <td class="py-3 px-4">@include('components.priority-badge', ['priority' => $req->priority])</td>
                                <td class="py-3 px-4">@include('components.status-badge', ['status' => $req->status])</td>
                                <td class="py-3 px-4 text-gray-400 dark:text-gray-500">{{ $req->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
