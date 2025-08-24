@extends('layouts.app')

@section('title', 'Reading Test')

@section('content')
<div id="reading-test" class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="flex justify-between items-center px-6 py-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $session->test->title }} - Reading</h1>
                <p class="text-sm text-gray-600">Student: {{ $session->student->full_name }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-sm text-gray-500">Time Remaining</div>
                    <div id="timer" class="text-lg font-mono font-bold text-red-600">{{ $session->test->reading_time }}:00</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Passage</div>
                    <div id="current-passage" class="text-lg font-bold">1</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen">
        <!-- Passages Section -->
        <div class="w-1/2 bg-white border-r border-gray-200">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Reading Passages</h2>
                    
                    <!-- Passage Navigation -->
                    <div class="flex space-x-2 mb-4">
                        @foreach($materials as $material)
                        <button class="passage-nav px-4 py-2 rounded-md text-sm font-medium {{ $loop->first ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}" 
                                data-passage="{{ $material->part }}">
                            Passage {{ $material->part }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Passages Content -->
                <div id="passages-container" class="space-y-6">
                    @foreach($materials as $material)
                    <div class="passage-content {{ $loop->first ? '' : 'hidden' }}" data-passage="{{ $material->part }}">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $material->title }}</h3>
                        </div>
                        
                        <div class="prose prose-sm max-w-none passage-text" data-passage-id="{{ $material->part }}">
                            {!! nl2br(e($material->content)) !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="w-1/2 bg-gray-50">
            <div class="p-6 h-full overflow-y-auto">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Questions</h2>
                    
                    <!-- Question Navigation -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($questions as $part => $partQuestions)
                        <button class="question-nav px-3 py-1 rounded-md text-sm font-medium {{ $loop->first ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}" 
                                data-part="{{ $part }}">
                            Part {{ $part }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Questions Content -->
                <div id="questions-container">
                    @foreach($questions as $part => $partQuestions)
                    <div class="part-questions {{ $loop->first ? '' : 'hidden' }}" data-part="{{ $part }}">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Part {{ $part }}</h3>
                            <p class="text-sm text-gray-600 mb-4">Answer the following {{ $partQuestions->count() }} questions based on the reading passage.</p>
                        </div>

                        <div class="space-y-6">
                            @foreach($partQuestions as $question)
                            <div class="bg-white rounded-lg shadow-sm border p-6 question-item" data-question-id="{{ $question->id }}">
                                <div class="mb-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-md font-medium text-gray-900">Question {{ $loop->index + 1 }}</h4>
                                        <span class="text-sm text-gray-500">{{ $question->points }} point(s)</span>
                                    </div>
                                    <p class="text-gray-700 mt-2">{{ $question->question_text }}</p>
                                </div>

                                <div class="question-options">
                                    @if($question->isMultipleChoice())
                                        <div class="space-y-3">
                                            @foreach($question->options_array as $index => $option)
                                            <label class="flex items-center space-x-3 cursor-pointer">
                                                <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}" 
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                                <span class="text-gray-700">{{ chr(65 + $index) }}. {{ $option }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    @elseif($question->isGapFilling())
                                        <div class="space-y-3">
                                            @foreach($question->correct_answers_array as $index => $answer)
                                            <div class="flex items-center space-x-3">
                                                <span class="text-gray-700">{{ $index + 1 }}.</span>
                                                <input type="text" name="question_{{ $question->id }}[]" 
                                                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                       placeholder="Enter your answer">
                                            </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->isSelectOptions())
                                        <div class="space-y-3">
                                            @foreach($question->options_array as $index => $option)
                                            <label class="flex items-center space-x-3 cursor-pointer">
                                                <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $option }}" 
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <span class="text-gray-700">{{ $option }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Navigation and Submit -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <button id="prev-passage" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous Passage
                        </button>
                        
                        <div class="flex space-x-4">
                            <button id="complete-module" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                                Complete Reading Module
                            </button>
                        </div>
                        
                        <button id="next-passage" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Next Passage
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Highlighting styles */
.highlight {
    background-color: #fef3c7;
    padding: 2px 4px;
    border-radius: 3px;
    cursor: pointer;
}

.highlight:hover {
    background-color: #fde68a;
}

.highlight.selected {
    background-color: #fbbf24;
}

/* Prevent text selection except in inputs */
* {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

input, textarea {
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}

/* Passage text styling */
.passage-text {
    line-height: 1.6;
    font-size: 14px;
}

.passage-text p {
    margin-bottom: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
let currentPassage = 1;
let totalPassages = {{ $materials->count() }};
let timeRemaining = {{ $session->test->reading_time * 60 }};
let timerInterval;
let highlights = [];

// Initialize the test
document.addEventListener('DOMContentLoaded', function() {
    startTimer();
    initializePassageNavigation();
    initializeQuestionNavigation();
    setupHighlighting();
    setupAntiCheating();
    startHeartbeat();
});

function startTimer() {
    timerInterval = setInterval(function() {
        timeRemaining--;
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        document.getElementById('timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            completeModule();
        }
    }, 1000);
}

function initializePassageNavigation() {
    document.getElementById('prev-passage').addEventListener('click', function() {
        if (currentPassage > 1) {
            showPassage(currentPassage - 1);
        }
    });
    
    document.getElementById('next-passage').addEventListener('click', function() {
        if (currentPassage < totalPassages) {
            showPassage(currentPassage + 1);
        }
    });
    
    // Passage navigation buttons
    document.querySelectorAll('.passage-nav').forEach(button => {
        button.addEventListener('click', function() {
            const passage = parseInt(this.dataset.passage);
            showPassage(passage);
        });
    });
}

function showPassage(passage) {
    // Hide all passages
    document.querySelectorAll('.passage-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show selected passage
    const targetPassage = document.querySelector(`.passage-content[data-passage="${passage}"]`);
    if (targetPassage) {
        targetPassage.classList.remove('hidden');
    }
    
    // Update navigation buttons
    document.querySelectorAll('.passage-nav').forEach(btn => {
        btn.classList.remove('bg-indigo-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    const targetNav = document.querySelector(`.passage-nav[data-passage="${passage}"]`);
    if (targetNav) {
        targetNav.classList.remove('bg-gray-200', 'text-gray-700');
        targetNav.classList.add('bg-indigo-600', 'text-white');
    }
    
    // Update current passage display
    currentPassage = passage;
    document.getElementById('current-passage').textContent = passage;
    
    // Update navigation buttons
    document.getElementById('prev-passage').disabled = passage === 1;
    document.getElementById('next-passage').disabled = passage === totalPassages;
}

function initializeQuestionNavigation() {
    // Question navigation buttons
    document.querySelectorAll('.question-nav').forEach(button => {
        button.addEventListener('click', function() {
            const part = parseInt(this.dataset.part);
            showQuestionPart(part);
        });
    });
}

function showQuestionPart(part) {
    // Hide all question parts
    document.querySelectorAll('.part-questions').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show selected part
    const targetPart = document.querySelector(`.part-questions[data-part="${part}"]`);
    if (targetPart) {
        targetPart.classList.remove('hidden');
    }
    
    // Update navigation
    document.querySelectorAll('.question-nav').forEach(btn => {
        btn.classList.remove('bg-indigo-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    const targetNav = document.querySelector(`.question-nav[data-part="${part}"]`);
    if (targetNav) {
        targetNav.classList.remove('bg-gray-200', 'text-gray-700');
        targetNav.classList.add('bg-indigo-600', 'text-white');
    }
}

function setupHighlighting() {
    document.querySelectorAll('.passage-text').forEach(passage => {
        passage.addEventListener('mouseup', function() {
            const selection = window.getSelection();
            if (selection.toString().length > 0) {
                const range = selection.getRangeAt(0);
                const highlightSpan = document.createElement('span');
                highlightSpan.className = 'highlight';
                highlightSpan.textContent = selection.toString();
                
                range.deleteContents();
                range.insertNode(highlightSpan);
                
                // Store highlight
                highlights.push({
                    passage: currentPassage,
                    text: selection.toString(),
                    element: highlightSpan
                });
                
                selection.removeAllRanges();
            }
        });
        
        // Double-click to remove highlights
        passage.addEventListener('dblclick', function(e) {
            if (e.target.classList.contains('highlight')) {
                const text = e.target.textContent;
                const textNode = document.createTextNode(text);
                e.target.parentNode.replaceChild(textNode, e.target);
                
                // Remove from highlights array
                highlights = highlights.filter(h => h.element !== e.target);
            }
        });
    });
}

function setupAntiCheating() {
    // Prevent keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a' || e.key === 'z' || e.key === 'y' || e.key === 's' || e.key === 'p')) || 
            e.key === 'F5' || 
            (e.ctrlKey && e.key === 'r')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Prevent right-click
    document.addEventListener('contextmenu', e => e.preventDefault());
    
    // Monitor visibility changes
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            recordCheatAttempt('Page hidden/tab switched');
        }
    });
    
    // Monitor window focus
    window.addEventListener('blur', function() {
        recordCheatAttempt('Window lost focus');
    });
}

function startHeartbeat() {
    setInterval(function() {
        fetch('{{ route("student.session.heartbeat", $session->session_token) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                fullscreen: false,
                focused: !document.hidden,
                right_click: false,
                keyboard_shortcut: false,
                tab_switch: false
            })
        });
    }, 30000); // Every 30 seconds
}

function recordCheatAttempt(attempt) {
    fetch('{{ route("student.session.heartbeat", $session->session_token) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            fullscreen: false,
            focused: !document.hidden,
            right_click: attempt.includes('right-click'),
            keyboard_shortcut: attempt.includes('keyboard'),
            tab_switch: attempt.includes('tab')
        })
    });
}

function completeModule() {
    clearInterval(timerInterval);
    
    fetch('{{ route("student.session.complete-module", $session->session_token) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            module: 'reading'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_completed) {
                window.location.href = '{{ route("student.session.show", $session->session_token) }}';
            } else {
                window.location.href = '{{ route("student.session.writing", $session->session_token) }}';
            }
        }
    });
}

// Complete module button
document.getElementById('complete-module').addEventListener('click', function() {
    if (confirm('Are you sure you want to complete the Reading module? You cannot return to it later.')) {
        completeModule();
    }
});

// Auto-save answers
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.startsWith('question_')) {
        const questionId = e.target.name.replace('question_', '');
        const answers = [];
        
        if (e.target.type === 'radio') {
            answers.push(e.target.value);
        } else if (e.target.type === 'checkbox') {
            document.querySelectorAll(`input[name="${e.target.name}"]:checked`).forEach(cb => {
                answers.push(cb.value);
            });
        } else if (e.target.type === 'text') {
            document.querySelectorAll(`input[name="${e.target.name}"]`).forEach(input => {
                if (input.value) answers.push(input.value);
            });
        }
        
        // Save answer
        fetch('{{ route("student.session.submit-answer", $session->session_token) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                question_id: questionId,
                answer: answers
            })
        });
    }
});
</script>
@endpush
@endsection 