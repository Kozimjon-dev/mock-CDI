@extends('layouts.app')

@section('title', 'Test Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $session->test->title }}</h1>
                    <p class="text-sm text-gray-600">Student: {{ $session->student->full_name }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Session ID</div>
                    <div class="text-xs font-mono text-gray-400">{{ $session->session_token }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Overview -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Test Progress</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Listening Module -->
                <div class="border rounded-lg p-4 {{ $session->current_module === 'listening' ? 'border-indigo-500 bg-indigo-50' : ($session->isModuleCompleted('listening') ? 'border-green-500 bg-green-50' : 'border-gray-200') }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Listening</h3>
                            <p class="text-sm text-gray-500">{{ $session->test->listening_time }} minutes</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($session->isModuleCompleted('listening'))
                                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($session->current_module === 'listening')
                                <div class="h-6 w-6 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <div class="h-2 w-2 bg-white rounded-full"></div>
                                </div>
                            @else
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    @if($session->current_module === 'listening')
                        <div class="mt-3">
                            <a href="{{ route('student.session.listening', $session->session_token) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Start Listening
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Reading Module -->
                <div class="border rounded-lg p-4 {{ $session->current_module === 'reading' ? 'border-indigo-500 bg-indigo-50' : ($session->isModuleCompleted('reading') ? 'border-green-500 bg-green-50' : 'border-gray-200') }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Reading</h3>
                            <p class="text-sm text-gray-500">{{ $session->test->reading_time }} minutes</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($session->isModuleCompleted('reading'))
                                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($session->current_module === 'reading')
                                <div class="h-6 w-6 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <div class="h-2 w-2 bg-white rounded-full"></div>
                                </div>
                            @else
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    @if($session->current_module === 'reading')
                        <div class="mt-3">
                            <a href="{{ route('student.session.reading', $session->session_token) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Start Reading
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Writing Module -->
                <div class="border rounded-lg p-4 {{ $session->current_module === 'writing' ? 'border-indigo-500 bg-indigo-50' : ($session->isModuleCompleted('writing') ? 'border-green-500 bg-green-50' : 'border-gray-200') }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Writing</h3>
                            <p class="text-sm text-gray-500">{{ $session->test->writing_time }} minutes</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($session->isModuleCompleted('writing'))
                                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($session->current_module === 'writing')
                                <div class="h-6 w-6 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <div class="h-2 w-2 bg-white rounded-full"></div>
                                </div>
                            @else
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    @if($session->current_module === 'writing')
                        <div class="mt-3">
                            <a href="{{ route('student.session.writing', $session->session_token) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Start Writing
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Important Instructions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Before Starting:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Ensure you have a stable internet connection</li>
                        <li>• Close all other applications and browser tabs</li>
                        <li>• Prepare a quiet environment for the listening test</li>
                        <li>• Have paper and pen ready for notes</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">During the Test:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Do not refresh or close the browser</li>
                        <li>• Do not use keyboard shortcuts (Ctrl+C, Ctrl+V, etc.)</li>
                        <li>• Stay in full-screen mode throughout the test</li>
                        <li>• Complete each module within the time limit</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Current Module Action -->
        @if($session->current_module !== 'completed')
        <div class="mt-8 text-center">
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                <h3 class="text-lg font-medium text-indigo-900 mb-2">
                    Ready to start {{ ucfirst($session->current_module) }}?
                </h3>
                <p class="text-indigo-700 mb-4">
                    Click the button below to begin the {{ $session->current_module }} module.
                </p>
                <a href="{{ route('student.session.' . $session->current_module, $session->session_token) }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Start {{ ucfirst($session->current_module) }} Module
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Prevent common keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Prevent Ctrl+C, Ctrl+V, Ctrl+X, Ctrl+A, Ctrl+Z, Ctrl+Y, Ctrl+S, Ctrl+P, F5, Ctrl+R
    if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a' || e.key === 'z' || e.key === 'y' || e.key === 's' || e.key === 'p')) || 
        e.key === 'F5' || 
        (e.ctrlKey && e.key === 'r')) {
        e.preventDefault();
        return false;
    }
});

// Prevent right-click context menu
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Prevent text selection
document.addEventListener('selectstart', function(e) {
    e.preventDefault();
    return false;
});

// Prevent drag and drop
document.addEventListener('dragstart', function(e) {
    e.preventDefault();
    return false;
});
</script>
@endpush
@endsection 