<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MOCK CDI IELTS') }} - @yield('title', 'Admin')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 relative">
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">MOCK CDI</a>
                <p class="text-gray-400 text-xs mt-1">Admin Panel</p>
            </div>
            <nav class="mt-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-6 py-3 text-sm {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.tests.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Tests
                </a>
                <a href="{{ route('admin.materials.index') }}"
                   class="flex items-center px-6 py-3 text-sm {{ request()->routeIs('admin.materials.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Materials
                </a>
                <a href="{{ route('admin.questions.index') }}"
                   class="flex items-center px-6 py-3 text-sm {{ request()->routeIs('admin.questions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Questions
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white" title="Logout">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700" target="_blank">View Site</a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-md p-4">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>