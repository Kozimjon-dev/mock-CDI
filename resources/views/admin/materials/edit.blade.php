@extends('layouts.admin')

@section('title', 'Edit Material')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Edit Material</h2>
        <p class="mt-1 text-gray-600">Editing "{{ $material->title }}" from {{ $material->test->title }}</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.materials.update', $material) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 space-y-6">
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700">Module *</label>
                    <select name="module" id="module" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="listening" {{ old('module', $material->module) == 'listening' ? 'selected' : '' }}>Listening</option>
                        <option value="reading" {{ old('module', $material->module) == 'reading' ? 'selected' : '' }}>Reading</option>
                        <option value="writing" {{ old('module', $material->module) == 'writing' ? 'selected' : '' }}>Writing</option>
                    </select>
                    @error('module') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="part" class="block text-sm font-medium text-gray-700">Part *</label>
                    <select name="part" id="part" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ old('part', $material->part) == $i ? 'selected' : '' }}>Part {{ $i }}</option>
                        @endfor
                    </select>
                    @error('part') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                    <select name="type" id="type" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="audio" {{ old('type', $material->type) == 'audio' ? 'selected' : '' }}>Audio</option>
                        <option value="text" {{ old('type', $material->type) == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="image" {{ old('type', $material->type) == 'image' ? 'selected' : '' }}>Image</option>
                    </select>
                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $material->title) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="8" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('content', $material->content) }}</textarea>
                    @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Replace File</label>
                    @if($material->file_name)
                        <p class="text-sm text-gray-500 mb-2">Current: {{ $material->file_name }}</p>
                    @endif
                    <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="order" id="order" min="1" value="{{ old('order', $material->order) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.show', $material->test) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update Material</button>
            </div>
        </form>
    </div>
</div>
@endsection
