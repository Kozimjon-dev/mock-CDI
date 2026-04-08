@extends('layouts.admin')

@section('title', 'Material Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $material->title }}</h2>
            <p class="mt-1 text-gray-600">Part of: <a href="{{ route('admin.tests.show', $material->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $material->test->title }}</a></p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.materials.edit', $material) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Edit</a>
            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this material?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Material Information</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Module</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $material->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($material->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($material->module) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($material->type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Part</dt>
                    <dd class="mt-1 text-sm text-gray-900">Part {{ $material->part }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Order</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $material->order }}</dd>
                </div>
                @if($material->file_name)
                <div>
                    <dt class="text-sm font-medium text-gray-500">File</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $material->file_name }} ({{ number_format(($material->file_size ?? 0) / 1024, 1) }} KB)</dd>
                </div>
                @endif
            </dl>

            @if($material->content)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Content</dt>
                <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4 whitespace-pre-wrap">{{ $material->content }}</dd>
            </div>
            @endif

            @if($material->type === 'audio' && $material->file_path)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Audio Preview</dt>
                <audio controls class="w-full">
                    <source src="{{ Storage::url($material->file_path) }}" type="{{ $material->mime_type }}">
                </audio>
            </div>
            @endif

            @if($material->type === 'image' && $material->file_path)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Image Preview</dt>
                <img src="{{ Storage::url($material->file_path) }}" alt="{{ $material->title }}" class="max-w-lg rounded-lg shadow">
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
