@extends('layouts.admin')

@section('title', 'Materials')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">All Materials</h2>
            <p class="mt-1 text-gray-600">Manage test materials across all tests.</p>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($materials->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Part</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($materials as $material)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $material->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('admin.tests.show', $material->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $material->test->title }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $material->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($material->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($material->module) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($material->type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Part {{ $material->part }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                        <a href="{{ route('admin.materials.show', $material) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <a href="{{ route('admin.materials.edit', $material) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                        <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this material?')" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($materials->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $materials->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No materials found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
