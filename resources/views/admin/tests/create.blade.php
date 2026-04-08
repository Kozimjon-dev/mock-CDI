@extends('layouts.admin')

@section('title', 'Create New Test')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Test</h1>
        <p class="mt-2 text-gray-600">Set up a new IELTS mock test with custom timing and settings.</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.tests.store') }}" method="POST">
            @csrf
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Test Information</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Test Title *</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror"
                           value="{{ old('title') }}" placeholder="e.g., IELTS Academic Mock Test 1">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                              placeholder="Brief description of the test content and difficulty level">{{ old('description') }}</textarea>
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
                                   value="{{ old('listening_time', 30) }}">
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
                                   value="{{ old('reading_time', 60) }}">
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
                                   value="{{ old('writing_time', 60) }}">
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
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Draft tests are not visible to students</p>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Information Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Next Steps</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>After creating the test, you'll need to:</p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Upload audio files for the listening module</li>
                                    <li>Add reading passages for the reading module</li>
                                    <li>Create writing task descriptions</li>
                                    <li>Add questions for each module</li>
                                    <li>Publish the test when ready</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Test
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 