<!DOCTYPE html>
<html dir="rtl" lang="ar" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة الطلبات')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                    }
                }
            }
        }
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        .sidebar-link { transition: all 0.15s; }
        .sidebar-link:hover { background: rgba(255,255,255,0.08); }
        .sidebar-link.active { background: rgba(255,255,255,0.12); }
        #sidebar-mobile { transition: transform 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-200 min-h-screen font-sans antialiased">

    {{-- Mobile sidebar overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    {{-- Mobile sidebar --}}
    <aside id="sidebar-mobile" class="fixed top-0 right-0 w-72 h-full bg-sidebar-light dark:bg-sidebar-dark text-white z-50 transform translate-x-full md:hidden overflow-y-auto">
        <div class="p-5 border-b border-white/10 flex items-center justify-between">
            <a href="{{ route('client.dashboard') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-primary flex items-center justify-center font-bold text-white">C</div>
                <span class="text-lg font-bold">لوحة العميل</span>
            </a>
            <button onclick="closeSidebar()" class="p-1 rounded hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="p-3 space-y-1">
            @php
                $navItems = [
                    ['route' => 'client.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1" />', 'label' => 'لوحة التحكم'],
                    ['route' => 'client.requests.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />', 'label' => 'طلباتي'],
                    ['route' => 'client.notifications.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />', 'label' => 'الإشعارات'],
                    ['route' => 'client.messages.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />', 'label' => 'الرسائل'],
                    ['route' => 'client.storage.index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />', 'label' => 'الملفات'],
                ];
            @endphp
            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ $active ? 'active text-white' : 'text-white/70' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                    {{ $item['label'] }}
                    @if($item['route'] === 'client.notifications.index')
                        @php $count = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                        @if($count > 0)
                            <span class="mr-auto bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $count }}</span>
                        @endif
                    @endif
                    @if($item['route'] === 'client.messages.index')
                        @php $count = auth()->user()->messages()->where('is_read', false)->count(); @endphp
                        @if($count > 0)
                            <span class="mr-auto bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $count }}</span>
                        @endif
                    @endif
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="flex min-h-screen">

        {{-- Desktop sidebar --}}
        <aside class="hidden md:flex w-60 lg:w-64 shrink-0 bg-sidebar-light dark:bg-sidebar-dark text-white flex-col border-l border-white/10 sticky top-0 h-screen overflow-y-auto">
            <div class="p-5 border-b border-white/10">
                <a href="{{ route('client.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-primary flex items-center justify-center font-bold text-white">C</div>
                    <span class="text-lg font-bold">لوحة العميل</span>
                </a>
            </div>
            <nav class="flex-1 p-3 space-y-1">
                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ $active ? 'active text-white' : 'text-white/70' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                        {{ $item['label'] }}
                        @if($item['route'] === 'client.notifications.index')
                            @php $count = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                            @if($count > 0)
                                <span class="mr-auto bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $count }}</span>
                            @endif
                        @endif
                        @if($item['route'] === 'client.messages.index')
                            @php $count = auth()->user()->messages()->where('is_read', false)->count(); @endphp
                            @if($count > 0)
                                <span class="mr-auto bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $count }}</span>
                            @endif
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary/80 flex items-center justify-center font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen min-w-0">

            {{-- Top Bar --}}
            <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-30">
                <div class="flex items-center justify-between h-14 px-4 sm:px-6">
                    <button onclick="openSidebar()" class="md:hidden p-2 -mr-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center gap-2 mr-auto">
                        <button onclick="toggleTheme()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="تبديل الوضع">
                            <svg class="w-5 h-5 hidden dark:inline text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <svg class="w-5 h-5 dark:hidden text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-500 transition-colors px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">خروج</button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 sm:px-6 py-6 space-y-6">
                    @if(session('success'))
                        <div class="bg-green-50 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar-mobile').classList.remove('translate-x-full');
            document.getElementById('sidebar-overlay').classList.remove('hidden');
        }
        function closeSidebar() {
            document.getElementById('sidebar-mobile').classList.add('translate-x-full');
            document.getElementById('sidebar-overlay').classList.add('hidden');
        }
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>
</body>
</html>
