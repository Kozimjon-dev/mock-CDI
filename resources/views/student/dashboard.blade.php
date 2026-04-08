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
            <h2 class="text-lg font-medium text-gray-900 mb-2">Test Progress</h2>
            <p class="text-sm text-gray-500 mb-4">Istalgan modulni tanlang va boshlang</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $modules = [
                        'listening' => ['label' => 'Listening', 'icon' => 'headphones', 'color' => 'purple', 'time' => $session->test->listening_time],
                        'reading' => ['label' => 'Reading', 'icon' => 'book', 'color' => 'blue', 'time' => $session->test->reading_time],
                        'writing' => ['label' => 'Writing', 'icon' => 'pencil', 'color' => 'green', 'time' => $session->test->writing_time],
                    ];
                @endphp

                @foreach($modules as $key => $mod)
                    @php
                        $completed = $session->isModuleCompleted($key);
                        $isCurrent = $session->current_module === $key;
                    @endphp
                    <div class="border-2 rounded-lg p-5 transition-all {{ $completed ? 'border-green-400 bg-green-50' : ($isCurrent ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50') }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $mod['label'] }}</h3>
                            @if($completed)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Completed
                                </span>
                            @elseif($isCurrent)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    In Progress
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Not Started
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mb-4">{{ $mod['time'] }} minutes</p>
                        @if($completed)
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-green-700 bg-green-100 cursor-not-allowed">
                                Tugatilgan
                            </span>
                        @else
                            <a href="{{ route('student.session.' . $key, $session->session_token) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ $isCurrent ? 'Davom ettirish' : 'Boshlash' }} — {{ $mod['label'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
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
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a' || e.key === 'z' || e.key === 'y' || e.key === 's' || e.key === 'p')) ||
        e.key === 'F5' ||
        (e.ctrlKey && e.key === 'r')) {
        e.preventDefault();
        return false;
    }
});
document.addEventListener('contextmenu', function(e) { e.preventDefault(); return false; });
document.addEventListener('selectstart', function(e) { e.preventDefault(); return false; });
document.addEventListener('dragstart', function(e) { e.preventDefault(); return false; });
</script>
@endpush
@endsection
