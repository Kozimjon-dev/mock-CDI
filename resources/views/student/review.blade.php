@extends('layouts.app')

@section('title', 'Review Answers')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('student.session.show', $session->session_token) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium inline-flex items-center mb-4">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Results
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Review Your Answers</h1>
            <p class="text-gray-600 mt-1">{{ $session->test->title }} &mdash; {{ $session->student->full_name }}</p>
        </div>

        <!-- Score Summary -->
        <div class="bg-white rounded-xl shadow p-4 mb-6 flex flex-wrap gap-4 justify-center text-sm">
            <div class="flex items-center gap-2">
                <span class="inline-block w-3 h-3 rounded-full bg-purple-500"></span>
                Listening: <strong>Band {{ number_format($scores['listening']['band'], 1) }}</strong>
                ({{ $scores['listening']['correct'] }}/{{ $scores['listening']['total'] }})
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span>
                Reading: <strong>Band {{ number_format($scores['reading']['band'], 1) }}</strong>
                ({{ $scores['reading']['correct'] }}/{{ $scores['reading']['total'] }})
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-3 h-3 rounded-full bg-indigo-500"></span>
                Overall: <strong>Band {{ number_format($scores['overall']['band'], 1) }}</strong>
            </div>
        </div>

        <!-- Module Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" id="module-tabs">
                    <button onclick="switchTab('listening')" id="tab-listening"
                            class="tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        Listening ({{ $scores['listening']['correct'] }}/{{ $scores['listening']['total'] }})
                    </button>
                    <button onclick="switchTab('reading')" id="tab-reading"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        Reading ({{ $scores['reading']['correct'] }}/{{ $scores['reading']['total'] }})
                    </button>
                    <button onclick="switchTab('writing')" id="tab-writing"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        Writing
                    </button>
                </nav>
            </div>
        </div>

        <!-- Listening Questions -->
        <div id="panel-listening" class="tab-panel space-y-4">
            @forelse($listeningQuestions as $index => $question)
                @php
                    $response = $responses->get($question->id);
                    $isCorrect = $response ? $response->is_correct : false;
                    $studentAnswer = $response ? $response->formatted_answer : 'No answer';
                    $correctAnswer = is_array($question->correct_answers) ? implode(', ', $question->correct_answers) : $question->correct_answers;
                @endphp
                <div class="bg-white rounded-lg shadow p-5 border-l-4 {{ $isCorrect ? 'border-green-500' : 'border-red-500' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-400">Q{{ $index + 1 }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 rounded px-2 py-0.5">Part {{ $question->part }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 rounded px-2 py-0.5">{{ $question->type_label }}</span>
                        </div>
                        @if($isCorrect)
                            <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 rounded-full px-2 py-1">
                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Correct
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-medium text-red-700 bg-red-50 rounded-full px-2 py-1">
                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                Wrong
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-900 text-sm mb-3">{!! nl2br(e($question->question_text)) !!}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        <div class="rounded-md p-2 {{ $isCorrect ? 'bg-green-50' : 'bg-red-50' }}">
                            <span class="text-xs font-medium {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">Your Answer:</span>
                            <p class="{{ $isCorrect ? 'text-green-800' : 'text-red-800' }} font-medium">{{ $studentAnswer }}</p>
                        </div>
                        @if(!$isCorrect)
                        <div class="rounded-md p-2 bg-green-50">
                            <span class="text-xs font-medium text-green-600">Correct Answer:</span>
                            <p class="text-green-800 font-medium">{{ $correctAnswer }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No listening questions found.</p>
            @endforelse
        </div>

        <!-- Reading Questions -->
        <div id="panel-reading" class="tab-panel space-y-4 hidden">
            @forelse($readingQuestions as $index => $question)
                @php
                    $response = $responses->get($question->id);
                    $isCorrect = $response ? $response->is_correct : false;
                    $studentAnswer = $response ? $response->formatted_answer : 'No answer';
                    $correctAnswer = is_array($question->correct_answers) ? implode(', ', $question->correct_answers) : $question->correct_answers;
                @endphp
                <div class="bg-white rounded-lg shadow p-5 border-l-4 {{ $isCorrect ? 'border-green-500' : 'border-red-500' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-400">Q{{ $index + 1 }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 rounded px-2 py-0.5">Part {{ $question->part }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 rounded px-2 py-0.5">{{ $question->type_label }}</span>
                        </div>
                        @if($isCorrect)
                            <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 rounded-full px-2 py-1">
                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Correct
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-medium text-red-700 bg-red-50 rounded-full px-2 py-1">
                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                Wrong
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-900 text-sm mb-3">{!! nl2br(e($question->question_text)) !!}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        <div class="rounded-md p-2 {{ $isCorrect ? 'bg-green-50' : 'bg-red-50' }}">
                            <span class="text-xs font-medium {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">Your Answer:</span>
                            <p class="{{ $isCorrect ? 'text-green-800' : 'text-red-800' }} font-medium">{{ $studentAnswer }}</p>
                        </div>
                        @if(!$isCorrect)
                        <div class="rounded-md p-2 bg-green-50">
                            <span class="text-xs font-medium text-green-600">Correct Answer:</span>
                            <p class="text-green-800 font-medium">{{ $correctAnswer }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No reading questions found.</p>
            @endforelse
        </div>

        <!-- Writing Responses -->
        <div id="panel-writing" class="tab-panel space-y-4 hidden">
            @foreach(['task_1' => 'Task 1', 'task_2' => 'Task 2'] as $taskKey => $taskLabel)
                @php $wr = $writingResponses->get($taskKey); @endphp
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $taskLabel }}</h3>
                        @if($wr)
                            <span class="text-xs text-gray-500">{{ $wr->word_count }} words</span>
                        @endif
                    </div>
                    @if($wr)
                        <div class="bg-gray-50 rounded-md p-4 text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $wr->response_content }}</div>
                    @else
                        <p class="text-gray-400 text-sm italic">No response submitted.</p>
                    @endif
                </div>
            @endforeach
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">Writing responses are reviewed and scored by your instructor separately.</p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function switchTab(module) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-indigo-500', 'text-indigo-600');
        b.classList.add('border-transparent', 'text-gray-500');
    });

    document.getElementById('panel-' + module).classList.remove('hidden');
    const btn = document.getElementById('tab-' + module);
    btn.classList.add('border-indigo-500', 'text-indigo-600');
    btn.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endpush
@endsection
