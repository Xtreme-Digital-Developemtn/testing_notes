@extends('layouts.client')
@section('title', 'طلباتي')
@section('content')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">طلباتي</h1>
        <a href="{{ route('client.requests.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors">+ طلب جديد</a>
    </div>

    <form method="GET" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <input type="text" name="search" placeholder="بحث بالعنوان أو الوصف..." value="{{ request('search') }}" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
            <select name="priority" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">كل الأولويات</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالية</option>
            </select>
            <select name="status" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm">
                <option value="">كل الحالات</option>
                <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>لم تبدأ</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="solved" {{ request('status') == 'solved' ? 'selected' : '' }}>تم الحل</option>
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
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gray-800 dark:bg-gray-700 text-white px-4 py-2.5 rounded-lg text-sm hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">بحث</button>
                <a href="{{ route('client.requests.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2.5 rounded-lg text-sm text-center hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">إعادة</a>
            </div>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        @if($requests->isEmpty())
            <p class="p-8 text-gray-400 dark:text-gray-500 text-sm text-center">لا توجد طلبات</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">العنوان</th>
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">الأولوية</th>
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">الحالة</th>
                            <th class="py-3 px-5 text-gray-500 dark:text-gray-400 font-medium">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="py-3.5 px-5">
                                    <a href="{{ route('client.requests.show', $req->id) }}" class="text-primary hover:underline font-medium">{{ $req->title }}</a>
                                </td>
                                <td class="py-3.5 px-5">@include('components.priority-badge', ['priority' => $req->priority])</td>
                                <td class="py-3.5 px-5">@include('components.status-badge', ['status' => $req->status])</td>
                                <td class="py-3.5 px-5 text-gray-400 dark:text-gray-500">{{ $req->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection
