@extends('layouts.admin')

@section('title', 'Add Material')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Material</h1>
                <p class="mt-2 text-gray-600">Add a new material to "{{ $test->title }}"</p>
            </div>
            <a href="{{ route('admin.tests.show', $test) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Test
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" id="materialForm">
            @csrf
            <input type="hidden" name="test_id" value="{{ $test->id }}">
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Material Details</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Module Selection -->
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700">Module *</label>
                    <select name="module" id="module" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('module') border-red-300 @enderror">
                        <option value="">Select Module</option>
                        <option value="listening" {{ old('module') == 'listening' ? 'selected' : '' }}>Listening</option>
                        <option value="reading" {{ old('module') == 'reading' ? 'selected' : '' }}>Reading</option>
                        <option value="writing" {{ old('module') == 'writing' ? 'selected' : '' }}>Writing</option>
                    </select>
                    @error('module')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Part Selection -->
                <div>
                    <label for="part" class="block text-sm font-medium text-gray-700">Part *</label>
                    <select name="part" id="part" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('part') border-red-300 @enderror">
                        <option value="">Select Part</option>
                        <option value="1" {{ old('part') == '1' ? 'selected' : '' }}>Part 1</option>
                        <option value="2" {{ old('part') == '2' ? 'selected' : '' }}>Part 2</option>
                        <option value="3" {{ old('part') == '3' ? 'selected' : '' }}>Part 3</option>
                        <option value="4" {{ old('part') == '4' ? 'selected' : '' }}>Part 4</option>
                    </select>
                    @error('part')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Material Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Material Type *</label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('type') border-red-300 @enderror">
                        <option value="">Select Type</option>
                        <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>Audio File</option>
                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text Content</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror"
                           value="{{ old('title') }}" placeholder="e.g., Listening Part 1 - Airport Announcements">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content (for text materials) -->
                <div id="contentSection" class="hidden">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content *</label>
                    <textarea name="content" id="content" rows="10"
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('content') border-red-300 @enderror"
                              placeholder="Enter the text content...">{{ old('content') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">For reading passages or writing prompts</p>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload (for audio and image materials) -->
                <div id="fileSection" class="hidden">
                    <label for="file" class="block text-sm font-medium text-gray-700">File *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="file" name="file" type="file" class="sr-only" accept="">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="fileTypeInfo">
                                <!-- File type info will be populated by JavaScript -->
                            </p>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="order" id="order" min="1"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('order') border-red-300 @enderror"
                           value="{{ old('order', 1) }}">
                    <p class="mt-1 text-sm text-gray-500">Material order within the part</p>
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metadata -->
                <div>
                    <label for="metadata" class="block text-sm font-medium text-gray-700">Additional Information</label>
                    <textarea name="metadata" id="metadata" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('metadata') border-red-300 @enderror"
                              placeholder="Any additional notes or instructions...">{{ old('metadata') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Optional notes for administrators</p>
                    @error('metadata')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.show', $test) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Material
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const contentSection = document.getElementById('contentSection');
    const fileSection = document.getElementById('fileSection');
    const fileInput = document.getElementById('file');
    const fileTypeInfo = document.getElementById('fileTypeInfo');
    
    typeSelect.addEventListener('change', function() {
        // Hide both sections initially
        contentSection.classList.add('hidden');
        fileSection.classList.add('hidden');
        
        // Show appropriate section based on type
        if (this.value === 'text') {
            contentSection.classList.remove('hidden');
        } else if (this.value === 'audio' || this.value === 'image') {
            fileSection.classList.remove('hidden');
            
            // Update file input accept attribute and info text
            if (this.value === 'audio') {
                fileInput.accept = 'audio/*';
                fileTypeInfo.textContent = 'MP3, WAV, M4A up to 50MB';
            } else if (this.value === 'image') {
                fileInput.accept = 'image/*';
                fileTypeInfo.textContent = 'PNG, JPG, JPEG up to 10MB';
            }
        }
    });
    
    // File input change handler
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate file size
            const maxSize = typeSelect.value === 'audio' ? 50 * 1024 * 1024 : 10 * 1024 * 1024; // 50MB for audio, 10MB for images
            if (file.size > maxSize) {
                alert('File size too large. Please select a smaller file.');
                this.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = typeSelect.value === 'audio' 
                ? ['audio/mpeg', 'audio/wav', 'audio/mp4', 'audio/aac']
                : ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please select a valid file.');
                this.value = '';
                return;
            }
        }
    });
});
</script>
@endpush
@endsection 