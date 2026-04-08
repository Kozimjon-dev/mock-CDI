@extends('layouts.admin')

@section('title', 'Admin Dashboard - Tests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Test Management</h1>
                <p class="mt-2 text-gray-600">Create and manage IELTS mock tests for your students.</p>
            </div>
            <a href="{{ route('admin.tests.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create New Test
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tests</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tests->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Published Tests</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tests->where('is_published', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Draft Tests</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tests->where('status', 'draft')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Tests</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tests->where('status', 'active')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tests Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Tests</h3>
        </div>
        
        @if($tests->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($tests as $test)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $test->title }}</h4>
                                    <div class="ml-2 flex items-center">
                                        @if($test->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Draft
                                            </span>
                                        @endif
                                        
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $test->status === 'active' ? 'bg-blue-100 text-blue-800' : 
                                                      ($test->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($test->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <p class="text-sm text-gray-600">{{ Str::limit($test->description, 100) }}</p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <span>Listening: {{ $test->listening_time }}min</span>
                                    <span class="mx-2">•</span>
                                    <span>Reading: {{ $test->reading_time }}min</span>
                                    <span class="mx-2">•</span>
                                    <span>Writing: {{ $test->writing_time }}min</span>
                                    <span class="mx-2">•</span>
                                    <span>Total: {{ $test->total_time }}min</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.tests.show', $test) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                View
                            </a>
                            <a href="{{ route('admin.tests.edit', $test) }}" 
                               class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                Edit
                            </a>
                            
                            @if($test->is_published)
                                <form action="{{ route('admin.tests.unpublish', $test) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                        Unpublish
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.tests.publish', $test) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        Publish
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this test?')" 
                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        
        <!-- Pagination -->
        @if($tests->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $tests->links() }}
        </div>
        @endif
        
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No tests</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new test.</p>
            <div class="mt-6">
                <a href="{{ route('admin.tests.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Test
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 