@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-purple-700">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 800 600" fill="none"><circle cx="400" cy="300" r="300" stroke="white" stroke-width="1"/><circle cx="400" cy="300" r="200" stroke="white" stroke-width="1"/><circle cx="400" cy="300" r="100" stroke="white" stroke-width="1"/></svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 relative z-10">
            <div class="text-center lg:text-left lg:max-w-2xl">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight">
                    Practice IELTS with
                    <span class="text-yellow-300">Mock CDI</span>
                </h1>
                <p class="mt-4 text-lg text-indigo-100 max-w-xl mx-auto lg:mx-0">
                    Experience realistic IELTS mock tests with instant band score results, detailed analytics, and progress tracking. Prepare smarter, score higher.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="{{ route('tests') }}"
                       class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-700 bg-white hover:bg-indigo-50 shadow-lg transition">
                        Start a Test
                    </a>
                    <a href="{{ route('student.history') }}"
                       class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-white/10 transition">
                        View My Results
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white -mt-8 relative z-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-6 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalTests }}</p>
                    <p class="text-sm text-gray-500 mt-1">Tests Available</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalStudents }}</p>
                    <p class="text-sm text-gray-500 mt-1">Students Registered</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-indigo-600">{{ $completedSessions }}</p>
                    <p class="text-sm text-gray-500 mt-1">Tests Completed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Modules</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900">Three IELTS Modules Covered</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Listening -->
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6a7 7 0 017 7M8.464 15.536a5 5 0 010-7.072M12 18a7 7 0 01-7-7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Listening</h3>
                    <p class="text-sm text-gray-600">Audio recordings with various accents, covering conversations, monologues, and academic discussions.</p>
                </div>
                <!-- Reading -->
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Reading</h3>
                    <p class="text-sm text-gray-600">Academic passages with multiple question types: multiple choice, true/false/not given, matching, and gap filling.</p>
                </div>
                <!-- Writing -->
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Writing</h3>
                    <p class="text-sm text-gray-600">Two writing tasks with word count tracking. Responses are reviewed and scored by instructors.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Process</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900">How It Works</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-lg font-bold text-indigo-600">1</span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Register</h3>
                    <p class="text-sm text-gray-600">Enter your name and phone number to start a test session.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-lg font-bold text-purple-600">2</span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Take the Test</h3>
                    <p class="text-sm text-gray-600">Complete Listening, Reading, and Writing modules under timed conditions.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-lg font-bold text-blue-600">3</span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Get Band Score</h3>
                    <p class="text-sm text-gray-600">Receive your IELTS band score instantly for Listening and Reading.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-lg font-bold text-green-600">4</span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Review & Improve</h3>
                    <p class="text-sm text-gray-600">Review your answers, track progress, and earn achievement badges.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900">Professional IELTS Testing Platform</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach([
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Secure Testing', 'desc' => 'Full-screen mode with anti-cheating measures for authentic exam conditions.'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Real Exam Conditions', 'desc' => 'Proper timing, module progression, and diverse question formats.'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Band Score Analytics', 'desc' => 'Instant IELTS band scores with visual charts and progress tracking.'],
                    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'User-Friendly', 'desc' => 'Intuitive interface with multi-language support (Uzbek, English, Russian).'],
                ] as $feature)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $feature['title'] }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $feature['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Available Tests -->
    @if($publishedTests->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Tests</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900">Available Mock Tests</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($publishedTests->take(6) as $test)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-6">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base font-semibold text-gray-900">{{ $test->title }}</h3>
                            <p class="text-xs text-gray-500">{{ $test->total_time }} minutes</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($test->description, 100) }}</p>
                    <a href="{{ route('student.register', $test) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Start Test
                    </a>
                </div>
                @endforeach
            </div>

            @if($publishedTests->count() > 6)
            <div class="mt-8 text-center">
                <a href="{{ route('tests') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition">
                    View All Tests
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
