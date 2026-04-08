@extends('layouts.app')

@section('title', 'Available Tests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Available IELTS Mock Tests</h1>
        <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">Choose a test and start practicing.</p>
    </div>

    @if($tests->count() > 0)
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($tests as $test)
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900">{{ $test->title }}</h3>
                </div>

                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($test->description, 120) }}</p>

                <div class="flex items-center text-xs text-gray-500 space-x-3 mb-4">
                    <span>Listening: {{ $test->listening_time }}m</span>
                    <span>Reading: {{ $test->reading_time }}m</span>
                    <span>Writing: {{ $test->writing_time }}m</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Total: {{ $test->total_time }} min</span>
                    <a href="{{ route('test.show', $test) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($tests->hasPages())
    <div class="mt-8">
        {{ $tests->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-16">
        <p class="text-lg text-gray-500">No tests available at the moment. Check back later.</p>
    </div>
    @endif
</div>
@endsection
