@extends('layouts.app')

@section('title', $test->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-indigo-600 px-6 py-8">
            <h1 class="text-3xl font-bold text-white">{{ $test->title }}</h1>
            <p class="mt-2 text-indigo-200">IELTS Mock Test</p>
        </div>

        <div class="px-6 py-6">
            <p class="text-gray-700 mb-6">{{ $test->description }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-purple-700">{{ $test->listening_time }} min</div>
                    <div class="text-sm text-purple-600">Listening</div>
                    <div class="text-xs text-purple-500 mt-1">{{ $test->listeningQuestions()->count() }} questions</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700">{{ $test->reading_time }} min</div>
                    <div class="text-sm text-blue-600">Reading</div>
                    <div class="text-xs text-blue-500 mt-1">{{ $test->readingQuestions()->count() }} questions</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-700">{{ $test->writing_time }} min</div>
                    <div class="text-sm text-green-600">Writing</div>
                    <div class="text-xs text-green-500 mt-1">{{ $test->writingQuestions()->count() }} tasks</div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-yellow-800 mb-2">Before you start:</h3>
                <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                    <li>Total test time: {{ $test->total_time }} minutes</li>
                    <li>Test runs in fullscreen mode — do not exit</li>
                    <li>Switching tabs or windows will be recorded</li>
                    <li>Copy/paste and keyboard shortcuts are disabled</li>
                    <li>Complete all modules: Listening, Reading, Writing</li>
                </ul>
            </div>

            <div class="text-center">
                <a href="{{ route('student.register', $test) }}"
                   class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                    Register & Start Test
                </a>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('tests') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to All Tests</a>
    </div>
</div>
@endsection
