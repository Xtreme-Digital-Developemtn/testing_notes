@extends('layouts.admin')
@section('title', 'العملاء')
@section('content')
    <h1 class="text-2xl font-bold">العملاء</h1>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($users->isEmpty())
            <p class="p-8 text-gray-400 dark:text-gray-500 text-sm text-center">لا يوجد عملاء</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">الاسم</th>
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">البريد الإلكتروني</th>
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">عدد الطلبات</th>
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">تاريخ التسجيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/20 dark:bg-primary/30 flex items-center justify-center text-primary text-sm font-bold">{{ substr($user->name, 0, 1) }}</div>
                                        <span class="font-medium">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-full text-xs font-medium">{{ $user->requests_count }} طلب</span>
                                </td>
                                <td class="py-3.5 px-5 text-gray-400 dark:text-gray-500">{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
