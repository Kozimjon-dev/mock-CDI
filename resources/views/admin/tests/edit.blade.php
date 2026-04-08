@extends('layouts.admin')

@section('title', 'Edit Test')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Test</h1>
        <p class="mt-2 text-gray-600">Update test settings and configuration.</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.tests.update', $test) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Test Information</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Test Title *</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror"
                           value="{{ old('title', $test->title) }}" placeholder="e.g., IELTS Academic Mock Test 1">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                              placeholder="Brief description of the test content and difficulty level">{{ old('description', $test->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timing Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Timing Settings (in minutes)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Listening Time -->
                        <div>
                            <label for="listening_time" class="block text-sm font-medium text-gray-700">Listening *</label>
                            <input type="number" name="listening_time" id="listening_time" required min="1" max="120"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('listening_time') border-red-300 @enderror"
                                   value="{{ old('listening_time', $test->listening_time) }}">
                            <p class="mt-1 text-sm text-gray-500">Standard: 30 minutes</p>
                            @error('listening_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reading Time -->
                        <div>
                            <label for="reading_time" class="block text-sm font-medium text-gray-700">Reading *</label>
                            <input type="number" name="reading_time" id="reading_time" required min="1" max="180"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('reading_time') border-red-300 @enderror"
                                   value="{{ old('reading_time', $test->reading_time) }}">
                            <p class="mt-1 text-sm text-gray-500">Standard: 60 minutes</p>
                            @error('reading_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Writing Time -->
                        <div>
                            <label for="writing_time" class="block text-sm font-medium text-gray-700">Writing *</label>
                            <input type="number" name="writing_time" id="writing_time" required min="1" max="180"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('writing_time') border-red-300 @enderror"
                                   value="{{ old('writing_time', $test->writing_time) }}">
                            <p class="mt-1 text-sm text-gray-500">Standard: 60 minutes</p>
                            @error('writing_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-300 @enderror">
                        <option value="draft" {{ old('status', $test->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $test->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $test->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Draft tests are not visible to students</p>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Test Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Current Test Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Published:</span>
                            <span class="ml-2 font-medium {{ $test->is_published ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $test->is_published ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Total Time:</span>
                            <span class="ml-2 font-medium">{{ $test->total_time }} minutes</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Materials:</span>
                            <span class="ml-2 font-medium">{{ $test->materials->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Questions:</span>
                            <span class="ml-2 font-medium">{{ $test->questions->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.show', $test) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Test
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 