@extends('layouts.app')

@section('title', 'Writing Test')

@section('content')
<div id="writing-test" class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="flex justify-between items-center px-6 py-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $session->test->title }} - Writing</h1>
                <p class="text-sm text-gray-600">Student: {{ $session->student->full_name }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-sm text-gray-500">Time Remaining</div>
                    <div id="timer" class="text-lg font-mono font-bold text-red-600">{{ $session->test->writing_time }}:00</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Task</div>
                    <div id="current-task" class="text-lg font-bold">1</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen">
        <!-- Writing Tasks Section -->
        <div class="w-1/2 bg-white border-r border-gray-200">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Writing Tasks</h2>
                    
                    <!-- Task Navigation -->
                    <div class="flex space-x-2 mb-4">
                        <button class="task-nav px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white" 
                                data-task="1">
                            Task 1
                        </button>
                        <button class="task-nav px-4 py-2 rounded-md text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" 
                                data-task="2">
                            Task 2
                        </button>
                    </div>
                </div>

                <!-- Writing Tasks Content -->
                <div id="tasks-container" class="space-y-6">
                    <!-- Task 1 -->
                    <div class="task-content" data-task="1">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Writing Task 1</h3>
                            <p class="text-sm text-gray-600 mb-4">You should spend about 20 minutes on this task.</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Task Description:</h4>
                            @if($writingQuestions->where('part', 1)->first())
                                <p class="text-gray-700">{{ $writingQuestions->where('part', 1)->first()->question_text }}</p>
                            @else
                                <p class="text-gray-700">Write at least 150 words describing the information shown in the chart/graph/diagram.</p>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label for="writing-task-1" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Response:
                            </label>
                            <textarea id="writing-task-1" name="task_1" rows="20" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                      placeholder="Write your response here...">{{ $existingResponses->get('task_1')->response_content ?? '' }}</textarea>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Word count: <span id="word-count-1" class="font-medium">0</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                Minimum: 150 words
                            </div>
                        </div>
                    </div>

                    <!-- Task 2 -->
                    <div class="task-content hidden" data-task="2">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Writing Task 2</h3>
                            <p class="text-sm text-gray-600 mb-4">You should spend about 40 minutes on this task.</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Task Description:</h4>
                            @if($writingQuestions->where('part', 2)->first())
                                <p class="text-gray-700">{{ $writingQuestions->where('part', 2)->first()->question_text }}</p>
                            @else
                                <p class="text-gray-700">Write at least 250 words responding to the essay question.</p>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label for="writing-task-2" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Response:
                            </label>
                            <textarea id="writing-task-2" name="task_2" rows="20" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                      placeholder="Write your response here...">{{ $existingResponses->get('task_2')->response_content ?? '' }}</textarea>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Word count: <span id="word-count-2" class="font-medium">0</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                Minimum: 250 words
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions and Progress Section -->
        <div class="w-1/2 bg-gray-50">
            <div class="p-6 h-full overflow-y-auto">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Instructions & Progress</h2>
                </div>

                <!-- Writing Guidelines -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h3 class="text-md font-bold text-gray-900 mb-4">Writing Guidelines</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Task 1 (20 minutes)</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Write at least 150 words</li>
                                <li>• Describe the information clearly and accurately</li>
                                <li>• Use appropriate vocabulary and grammar</li>
                                <li>• Organize your response logically</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Task 2 (40 minutes)</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Write at least 250 words</li>
                                <li>• Present a clear argument or position</li>
                                <li>• Support your ideas with examples</li>
                                <li>• Use a range of vocabulary and structures</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Progress Tracking -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h3 class="text-md font-bold text-gray-900 mb-4">Progress</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700">Task 1 Completion:</span>
                            <span id="task-1-progress" class="text-sm font-medium">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="task-1-bar" class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700">Task 2 Completion:</span>
                            <span id="task-2-progress" class="text-sm font-medium">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="task-2-bar" class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h3 class="text-md font-bold text-blue-900 mb-4">Writing Tips</h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li>• Plan your response before writing</li>
                        <li>• Use clear topic sentences and supporting details</li>
                        <li>• Vary your sentence structures</li>
                        <li>• Check your grammar and spelling</li>
                        <li>• Leave time to review and edit your work</li>
                    </ul>
                </div>

                <!-- Navigation and Submit -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <button id="prev-task" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous Task
                        </button>
                        
                        <div class="flex space-x-4">
                            <button id="complete-module" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                                Complete Writing Module
                            </button>
                        </div>
                        
                        <button id="next-task" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Next Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentTask = 1;
let timeRemaining = {{ $session->test->writing_time * 60 }};
let timerInterval;
let autoSaveInterval;

// Initialize the test
document.addEventListener('DOMContentLoaded', function() {
    startTimer();
    initializeTaskNavigation();
    setupWordCount();
    setupAutoSave();
    setupAntiCheating();
    startHeartbeat();
    updateProgress();
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

function initializeTaskNavigation() {
    document.getElementById('prev-task').addEventListener('click', function() {
        if (currentTask > 1) {
            showTask(currentTask - 1);
        }
    });
    
    document.getElementById('next-task').addEventListener('click', function() {
        if (currentTask < 2) {
            showTask(currentTask + 1);
        }
    });
    
    // Task navigation buttons
    document.querySelectorAll('.task-nav').forEach(button => {
        button.addEventListener('click', function() {
            const task = parseInt(this.dataset.task);
            showTask(task);
        });
    });
}

function showTask(task) {
    // Hide all tasks
    document.querySelectorAll('.task-content').forEach(el => el.classList.add('hidden'));
    
    // Show selected task
    document.querySelector(`[data-task="${task}"]`).classList.remove('hidden');
    
    // Update navigation
    document.querySelectorAll('.task-nav').forEach(btn => {
        btn.classList.remove('bg-indigo-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    document.querySelector(`[data-task="${task}"]`).classList.remove('bg-gray-200', 'text-gray-700');
    document.querySelector(`[data-task="${task}"]`).classList.add('bg-indigo-600', 'text-white');
    
    // Update current task display
    currentTask = task;
    document.getElementById('current-task').textContent = task;
    
    // Update navigation buttons
    document.getElementById('prev-task').disabled = task === 1;
    document.getElementById('next-task').disabled = task === 2;
}

function setupWordCount() {
    const task1Textarea = document.getElementById('writing-task-1');
    const task2Textarea = document.getElementById('writing-task-2');
    
    function updateWordCount(textarea, countElement) {
        const text = textarea.value;
        const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
        countElement.textContent = words;
        updateProgress();
    }
    
    task1Textarea.addEventListener('input', function() {
        updateWordCount(this, document.getElementById('word-count-1'));
    });
    
    task2Textarea.addEventListener('input', function() {
        updateWordCount(this, document.getElementById('word-count-2'));
    });
    
    // Initial word count
    updateWordCount(task1Textarea, document.getElementById('word-count-1'));
    updateWordCount(task2Textarea, document.getElementById('word-count-2'));
}

function updateProgress() {
    const task1Words = parseInt(document.getElementById('word-count-1').textContent);
    const task2Words = parseInt(document.getElementById('word-count-2').textContent);
    
    // Task 1 progress (150 words = 100%)
    const task1Progress = Math.min((task1Words / 150) * 100, 100);
    document.getElementById('task-1-progress').textContent = Math.round(task1Progress) + '%';
    document.getElementById('task-1-bar').style.width = task1Progress + '%';
    
    // Task 2 progress (250 words = 100%)
    const task2Progress = Math.min((task2Words / 250) * 100, 100);
    document.getElementById('task-2-progress').textContent = Math.round(task2Progress) + '%';
    document.getElementById('task-2-bar').style.width = task2Progress + '%';
}

function setupAutoSave() {
    autoSaveInterval = setInterval(function() {
        saveWritingResponses();
    }, 30000); // Auto-save every 30 seconds
}

function saveWritingResponses() {
    const task1Content = document.getElementById('writing-task-1').value;
    const task2Content = document.getElementById('writing-task-2').value;
    
    // Save Task 1
    if (task1Content.trim()) {
        fetch('{{ route("student.session.submit-writing", $session->session_token) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                task: 'task_1',
                content: task1Content
            })
        });
    }
    
    // Save Task 2
    if (task2Content.trim()) {
        fetch('{{ route("student.session.submit-writing", $session->session_token) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                task: 'task_2',
                content: task2Content
            })
        });
    }
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
    clearInterval(autoSaveInterval);
    
    // Save final responses
    saveWritingResponses();
    
    fetch('{{ route("student.session.complete-module", $session->session_token) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            module: 'writing'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_completed) {
                window.location.href = '{{ route("student.session.show", $session->session_token) }}';
            } else {
                window.location.href = '{{ route("student.session.show", $session->session_token) }}';
            }
        }
    });
}

// Complete module button
document.getElementById('complete-module').addEventListener('click', function() {
    if (confirm('Are you sure you want to complete the Writing module? You cannot return to it later.')) {
        completeModule();
    }
});

// Save on page unload
window.addEventListener('beforeunload', function() {
    saveWritingResponses();
});
</script>
@endpush
@endsection 