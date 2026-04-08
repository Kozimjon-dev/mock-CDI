@extends('layouts.admin')

@section('title', 'Question Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Question Details</h2>
            <p class="mt-1 text-gray-600">From: <a href="{{ route('admin.tests.show', $question->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $question->test->title }}</a></p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Edit</a>
            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this question?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Question Information</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Module</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $question->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($question->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($question->module) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Part</dt>
                    <dd class="mt-1 text-sm text-gray-900">Part {{ $question->part }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Points</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $question->points }}</dd>
                </div>
            </dl>

            <div class="mb-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Question Text</dt>
                <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4">{{ $question->question_text }}</dd>
            </div>

            @if(!empty($question->options))
            <div class="mb-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Options</dt>
                <dd>
                    <ul class="space-y-1">
                        @foreach($question->options as $i => $option)
                        <li class="text-sm text-gray-900 flex items-center">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-xs font-medium text-gray-700 mr-2">{{ chr(65 + $i) }}</span>
                            {{ $option }}
                            @if(in_array($option, $question->correct_answers ?? []))
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Correct</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </dd>
            </div>
            @endif

            <div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Correct Answers</dt>
                <dd class="flex flex-wrap gap-2">
                    @foreach($question->correct_answers ?? [] as $answer)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">{{ $answer }}</span>
                    @endforeach
                </dd>
            </div>
        </div>
    </div>
</div>
@endsection
