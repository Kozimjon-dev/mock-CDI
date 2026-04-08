@extends('layouts.admin')

@section('title', 'Questions')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">All Questions</h2>
        <p class="mt-1 text-gray-600">Manage test questions across all tests.</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($questions->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($questions as $question)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ Str::limit($question->question_text, 60) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('admin.tests.show', $question->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $question->test->title }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $question->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($question->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($question->module) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $question->points }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                        <a href="{{ route('admin.questions.show', $question) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <a href="{{ route('admin.questions.edit', $question) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this question?')" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($questions->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $questions->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No questions found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
