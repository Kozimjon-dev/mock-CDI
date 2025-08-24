@extends('layouts.app')

@section('title', 'Test Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $test->title }}</h1>
                <p class="mt-2 text-gray-600">{{ $test->description }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.tests.edit', $test) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Test
                </a>
                @if($test->is_published)
                    <form action="{{ route('admin.tests.unpublish', $test) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            Unpublish
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.tests.publish', $test) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Publish
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Test Information -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Test Information</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                   {{ $test->status === 'active' ? 'bg-green-100 text-green-800' : 
                                      ($test->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($test->status) }}
                        </span>
                        @if($test->is_published)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Published
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Listening Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $test->listening_time }} minutes</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Reading Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $test->reading_time }} minutes</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Writing Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $test->writing_time }} minutes</dd>
                </div>
            </div>
            <div class="mt-4">
                <dt class="text-sm font-medium text-gray-500">Total Time</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $test->total_time }} minutes</dd>
            </div>
        </div>
    </div>

    <!-- Materials Section -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Materials</h3>
                <a href="{{ route('admin.materials.create') }}?test={{ $test->id }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                    Add Material
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            @if($test->materials->count() > 0)
                <div class="space-y-4">
                    @foreach($test->materials->groupBy('module') as $module => $materials)
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">{{ ucfirst($module) }} Materials</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($materials as $material)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-900">{{ $material->title }}</h5>
                                                <p class="text-xs text-gray-500">Part {{ $material->part }}</p>
                                                <p class="text-xs text-gray-500">{{ ucfirst($material->type) }}</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.materials.edit', $material) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No materials</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding materials for this test.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.materials.create') }}?test={{ $test->id }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Material
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Questions Section -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Questions</h3>
                <a href="{{ route('admin.questions.create') }}?test={{ $test->id }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                    Add Question
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            @if($test->questions->count() > 0)
                <div class="space-y-6">
                    @foreach($test->questions->groupBy('module') as $module => $questions)
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">{{ ucfirst($module) }} Questions</h4>
                            <div class="space-y-3">
                                @foreach($questions->groupBy('part') as $part => $partQuestions)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Part {{ $part }} ({{ $partQuestions->count() }} questions)</h5>
                                        <div class="space-y-2">
                                            @foreach($partQuestions as $question)
                                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                                    <div class="flex-1">
                                                        <p class="text-sm text-gray-900">{{ Str::limit($question->question_text, 100) }}</p>
                                                        <div class="flex items-center space-x-4 mt-1">
                                                            <span class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                                            <span class="text-xs text-gray-500">{{ $question->points }} point(s)</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No questions</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding questions for this test.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.questions.create') }}?test={{ $test->id }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Question
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Test Statistics -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Test Statistics</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <dt class="text-sm font-medium text-gray-500">Total Sessions</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $test->testSessions->count() }}</dd>
                </div>
                <div class="text-center">
                    <dt class="text-sm font-medium text-gray-500">Completed Sessions</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $test->testSessions->whereNotNull('completed_at')->count() }}</dd>
                </div>
                <div class="text-center">
                    <dt class="text-sm font-medium text-gray-500">Active Sessions</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $test->testSessions->whereNull('completed_at')->count() }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.tests.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tests
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.tests.sessions', $test) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View Sessions
            </a>
            <a href="{{ route('admin.tests.results', $test) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                View Results
            </a>
        </div>
    </div>
</div>
@endsection 