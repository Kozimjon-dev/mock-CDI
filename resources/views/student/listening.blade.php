@extends('layouts.app')

@section('title', 'Listening Test')

@section('content')
<div id="listening-test" class="min-h-screen bg-gray-900 text-white">
    <!-- Full-screen header -->
    <div class="bg-gray-800 border-b border-gray-700">
        <div class="flex justify-between items-center px-6 py-4">
            <div>
                <h1 class="text-xl font-bold">{{ $session->test->title }} - Listening</h1>
                <p class="text-sm text-gray-400">Student: {{ $session->student->full_name }}</p>
            </div>
            <div class="flex items-center space-x-6">
                <div class="text-right">
                    <div class="text-sm text-gray-400">Questions</div>
                    <div class="text-lg font-bold"><span id="answered-count">0</span> / <span id="total-count">{{ $questions->flatten()->count() }}</span></div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">Time Remaining</div>
                    <div id="timer" class="text-lg font-mono font-bold text-red-400">{{ $session->test->listening_time }}:00</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">Part</div>
                    <div id="current-part" class="text-lg font-bold">1</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex" style="height: calc(100vh - 64px);">
        <!-- Left Panel — Audio + Navigation -->
        <div class="w-1/3 bg-gray-800 p-6 border-r border-gray-700 overflow-y-auto">
            <!-- Part Navigation -->
            <div class="mb-6">
                <h3 class="text-md font-bold mb-3">Parts</h3>
                <div class="space-y-2">
                    @foreach($questions as $part => $partQuestions)
                    <button class="part-nav w-full text-left px-3 py-2 rounded {{ $loop->first ? 'bg-indigo-600' : 'bg-gray-700 hover:bg-gray-600' }}"
                            data-part="{{ $part }}">
                        <div class="flex justify-between items-center">
                            <span>Part {{ $part }} ({{ $partQuestions->count() }} questions)</span>
                            <span class="text-xs text-gray-400 part-status" data-part="{{ $part }}">
                                @if($loop->first) Current @else Upcoming @endif
                            </span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Audio Player for Current Part -->
            <div class="mb-6">
                <h2 class="text-lg font-bold mb-4">Audio Player</h2>
                @foreach($audioMaterials as $part => $material)
                <div class="audio-player-section {{ $loop->first ? '' : 'hidden' }}" data-audio-part="{{ $part }}">
                    <div class="bg-gray-700 rounded-lg p-4">
                        <div class="mb-3">
                            <div class="text-white font-medium text-sm">{{ $material->title }}</div>
                            @if($material->content)
                            <p class="text-gray-400 text-xs mt-1">{{ Str::limit($material->content, 100) }}</p>
                            @endif
                        </div>

                        <audio class="audio-element" data-part="{{ $part }}" preload="auto" style="display:none;">
                            @if($material->file_url)
                            <source src="{{ $material->file_url }}" type="{{ $material->mime_type }}">
                            @endif
                        </audio>

                        <div class="bg-gray-600 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-300">Progress</span>
                                <span class="audio-time text-xs text-gray-300" data-part="{{ $part }}">0:00 / 0:00</span>
                            </div>
                            <div class="w-full bg-gray-500 rounded-full h-2 mb-2">
                                <div class="audio-progress bg-green-500 h-2 rounded-full transition-all duration-100" data-part="{{ $part }}" style="width:0%"></div>
                            </div>
                            <div class="flex justify-center space-x-3">
                                <button class="start-audio bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-md text-sm font-medium" data-part="{{ $part }}">
                                    Start Audio
                                </button>
                                <button class="stop-audio bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-md text-sm font-medium hidden" data-part="{{ $part }}">
                                    Stop
                                </button>
                            </div>
                            <div class="text-center mt-2">
                                <span class="audio-status text-xs text-gray-300" data-part="{{ $part }}">Ready to start</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($audioMaterials->isEmpty())
                <div class="bg-yellow-900 rounded-lg p-4">
                    <div class="text-yellow-200 text-sm">No audio files uploaded yet. Answer questions based on the context provided.</div>
                </div>
                @endif
            </div>

            <!-- Security notice -->
            <div class="bg-red-900 rounded-lg p-3 mb-6">
                <div class="text-red-200 text-sm">
                    <strong>Security Notice:</strong> Audio cannot be paused or replayed. Once started, it will play completely.
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-900 rounded-lg p-4">
                <h3 class="text-md font-bold mb-2">Instructions</h3>
                <ul class="text-sm text-blue-200 space-y-1">
                    <li>* Listen to the audio carefully</li>
                    <li>* Answer questions as you listen</li>
                    <li>* You cannot pause or replay the audio</li>
                    <li>* Each part has its own audio recording</li>
                    <li>* Complete all 4 parts within the time limit</li>
                </ul>
            </div>
        </div>

        <!-- Right Panel — Questions -->
        <div class="w-2/3 bg-gray-900 p-6 overflow-y-auto">
            <div id="questions-container">
                @foreach($questions as $part => $partQuestions)
                <div class="part-questions {{ $loop->first ? '' : 'hidden' }}" data-part="{{ $part }}">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold mb-2">Part {{ $part }}</h2>
                        <p class="text-gray-400 mb-1">Questions {{ ($part - 1) * 10 + 1 }} — {{ ($part - 1) * 10 + $partQuestions->count() }}</p>
                        @if(isset($audioMaterials[$part]) && $audioMaterials[$part]->content)
                        <p class="text-gray-500 text-sm italic">{{ Str::limit($audioMaterials[$part]->content, 200) }}</p>
                        @endif
                    </div>

                    <div class="space-y-8">
                        @foreach($partQuestions as $question)
                        <div class="bg-gray-800 rounded-lg p-6 question-item" data-question-id="{{ $question->id }}">
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium">Question {{ ($part - 1) * 10 + $loop->iteration }}</h3>
                                    <span class="text-sm text-gray-400">{{ $question->points }} point(s)</span>
                                </div>
                                @if(!$question->isSentenceCompletion())
                                <p class="text-gray-300 mt-2">{{ $question->question_text }}</p>
                                @endif
                            </div>

                            <div class="question-options">
                                @include('partials.question-types', ['question' => $question, 'theme' => 'dark'])
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Navigation and Submit -->
            <div class="mt-8 pt-6 border-t border-gray-700">
                <div class="flex justify-between items-center">
                    <button id="prev-part" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Previous Part
                    </button>

                    <div class="flex space-x-4">
                        <button id="complete-module" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                            Complete Listening Module
                        </button>
                    </div>

                    <button id="next-part" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        Next Part
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
#listening-test {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
}
* {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
input, textarea, select {
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}
</style>
@endpush

@push('scripts')
<script>
let currentPart = 1;
let totalParts = {{ $questions->count() }};
let timeRemaining = {{ $session->test->listening_time * 60 }};
let timerInterval;
let audioStates = {}; // Track audio state per part

document.addEventListener('DOMContentLoaded', function() {
    initializeFullscreen();
    startTimer();
    initializePartNavigation();
    initializeAudioPlayers();
    setupAntiCheating();
    startHeartbeat();
});

function initializeFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {});
    }
}

function startTimer() {
    timerInterval = setInterval(function() {
        timeRemaining--;
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        document.getElementById('timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        if (timeRemaining <= 300) {
            document.getElementById('timer').classList.add('animate-pulse');
        }
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            completeModule();
        }
    }, 1000);
}

function initializePartNavigation() {
    document.getElementById('prev-part').addEventListener('click', function() {
        if (currentPart > 1) showPart(currentPart - 1);
    });
    document.getElementById('next-part').addEventListener('click', function() {
        if (currentPart < totalParts) showPart(currentPart + 1);
    });
    document.querySelectorAll('.part-nav').forEach(button => {
        button.addEventListener('click', function() {
            showPart(parseInt(this.dataset.part));
        });
    });
}

function showPart(part) {
    // Hide all parts
    document.querySelectorAll('.part-questions').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.audio-player-section').forEach(el => el.classList.add('hidden'));

    // Show selected part
    const partQuestions = document.querySelector(`.part-questions[data-part="${part}"]`);
    if (partQuestions) partQuestions.classList.remove('hidden');

    const audioSection = document.querySelector(`.audio-player-section[data-audio-part="${part}"]`);
    if (audioSection) audioSection.classList.remove('hidden');

    // Update nav buttons
    document.querySelectorAll('.part-nav').forEach(btn => {
        btn.classList.remove('bg-indigo-600');
        btn.classList.add('bg-gray-700');
    });
    const navBtn = document.querySelector(`.part-nav[data-part="${part}"]`);
    if (navBtn) {
        navBtn.classList.remove('bg-gray-700');
        navBtn.classList.add('bg-indigo-600');
    }

    currentPart = part;
    document.getElementById('current-part').textContent = part;
    document.getElementById('prev-part').disabled = part === 1;
    document.getElementById('next-part').disabled = part === totalParts;
}

function initializeAudioPlayers() {
    document.querySelectorAll('.start-audio').forEach(btn => {
        btn.addEventListener('click', function() {
            const part = this.dataset.part;
            if (audioStates[part]) return; // Already started

            const audio = document.querySelector(`.audio-element[data-part="${part}"]`);
            if (!audio || !audio.querySelector('source')?.src) {
                // No audio file — mark as no audio available
                this.textContent = 'No Audio File';
                this.disabled = true;
                this.className = 'bg-gray-600 text-white px-4 py-1.5 rounded-md text-sm font-medium cursor-not-allowed';
                const status = document.querySelector(`.audio-status[data-part="${part}"]`);
                if (status) { status.textContent = 'No audio file uploaded'; status.className = 'audio-status text-xs text-yellow-400'; }
                return;
            }

            audio.play().then(() => {
                audioStates[part] = true;
                this.classList.add('hidden');
                document.querySelector(`.stop-audio[data-part="${part}"]`).classList.remove('hidden');
                const status = document.querySelector(`.audio-status[data-part="${part}"]`);
                status.textContent = 'Playing...';
                status.className = 'audio-status text-xs text-green-400';

                // Progress tracking
                const interval = setInterval(() => {
                    if (audio.duration && !isNaN(audio.duration)) {
                        const progress = (audio.currentTime / audio.duration) * 100;
                        document.querySelector(`.audio-progress[data-part="${part}"]`).style.width = progress + '%';
                        const timeEl = document.querySelector(`.audio-time[data-part="${part}"]`);
                        timeEl.textContent = `${formatTime(audio.currentTime)} / ${formatTime(audio.duration)}`;
                    }
                }, 100);
                audio.dataset.interval = interval;
            }).catch(() => {
                const status = document.querySelector(`.audio-status[data-part="${part}"]`);
                status.textContent = 'Failed to start audio';
                status.className = 'audio-status text-xs text-red-400';
            });

            audio.addEventListener('ended', () => {
                clearInterval(audio.dataset.interval);
                this.classList.remove('hidden');
                document.querySelector(`.stop-audio[data-part="${part}"]`).classList.add('hidden');
                this.disabled = true;
                this.textContent = 'Audio Completed';
                this.className = 'bg-gray-600 text-white px-4 py-1.5 rounded-md text-sm font-medium cursor-not-allowed';
                const status = document.querySelector(`.audio-status[data-part="${part}"]`);
                status.textContent = 'Completed';
                status.className = 'audio-status text-xs text-green-400';
            });
        });
    });

    document.querySelectorAll('.stop-audio').forEach(btn => {
        btn.addEventListener('click', function() {
            const part = this.dataset.part;
            const audio = document.querySelector(`.audio-element[data-part="${part}"]`);
            audio.pause();
            audio.currentTime = 0;
            clearInterval(audio.dataset.interval);

            this.classList.add('hidden');
            const startBtn = document.querySelector(`.start-audio[data-part="${part}"]`);
            startBtn.classList.remove('hidden');
            startBtn.disabled = true;
            startBtn.textContent = 'Audio Completed';
            startBtn.className = 'bg-gray-600 text-white px-4 py-1.5 rounded-md text-sm font-medium cursor-not-allowed';
            const status = document.querySelector(`.audio-status[data-part="${part}"]`);
            status.textContent = 'Stopped — cannot restart';
            status.className = 'audio-status text-xs text-red-400';
        });
    });
}

function formatTime(seconds) {
    if (isNaN(seconds)) return '0:00';
    return Math.floor(seconds / 60) + ':' + Math.floor(seconds % 60).toString().padStart(2, '0');
}

function setupAntiCheating() {
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey && 'cvxazysp'.includes(e.key)) || e.key === 'F5' || (e.ctrlKey && e.key === 'r') || e.key === 'Escape') {
            e.preventDefault();
            return false;
        }
        if (e.key === ' ' || e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        }
    });
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('selectstart', e => e.preventDefault());
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) recordCheatAttempt('Exited fullscreen mode');
    });
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) recordCheatAttempt('Page hidden/tab switched');
    });
    window.addEventListener('blur', function() { recordCheatAttempt('Window lost focus'); });
}

function startHeartbeat() {
    setInterval(function() {
        fetch('{{ route("student.session.heartbeat", $session->session_token) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ fullscreen: !!document.fullscreenElement, focused: !document.hidden, right_click: false, keyboard_shortcut: false, tab_switch: false })
        });
    }, 30000);
}

function recordCheatAttempt(attempt) {
    fetch('{{ route("student.session.heartbeat", $session->session_token) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ fullscreen: !!document.fullscreenElement, focused: !document.hidden, right_click: attempt.includes('right-click'), keyboard_shortcut: attempt.includes('keyboard'), tab_switch: attempt.includes('tab') })
    });
}

function completeModule() {
    clearInterval(timerInterval);
    // Stop all audio
    document.querySelectorAll('.audio-element').forEach(a => { a.pause(); clearInterval(a.dataset.interval); });

    fetch('{{ route("student.session.complete-module", $session->session_token) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ module: 'listening' })
    })
    .then(r => r.json())
    .then(data => { if (data.success) window.location.href = '{{ route("student.session.show", $session->session_token) }}'; });
}

document.getElementById('complete-module').addEventListener('click', function() {
    if (confirm('Are you sure you want to complete the Listening module? You cannot return to it later.')) completeModule();
});

// Auto-save answers
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.startsWith('question_')) {
        const questionId = e.target.name.replace(/question_(\d+).*/, '$1');
        let answer;

        if (e.target.type === 'radio') {
            answer = e.target.value;
        } else if (e.target.type === 'checkbox') {
            const checked = [];
            document.querySelectorAll(`input[name="${e.target.name}"]:checked`).forEach(cb => checked.push(cb.value));
            answer = checked;
        } else if (e.target.tagName === 'SELECT') {
            const match = e.target.name.match(/question_(\d+)\[(\d+)\]/);
            if (match) {
                const qId = match[1];
                const answers = {};
                document.querySelectorAll(`select[name^="question_${qId}["]`).forEach(sel => {
                    const m = sel.name.match(/\[(\d+)\]/);
                    if (m) answers[m[1]] = sel.value;
                });
                submitAnswer(qId, answers);
                return;
            }
            answer = e.target.value;
        } else if (e.target.type === 'text') {
            if (e.target.name.includes('[]')) {
                const texts = [];
                document.querySelectorAll(`input[name="${e.target.name}"]`).forEach(input => texts.push(input.value || ''));
                answer = texts;
            } else {
                answer = e.target.value;
            }
        }
        submitAnswer(questionId, answer);
        updateAnsweredCount();
    }
});

function submitAnswer(questionId, answer) {
    fetch('{{ route("student.session.submit-answer", $session->session_token) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ question_id: questionId, answer: answer })
    });
}

function updateAnsweredCount() {
    let count = 0;
    document.querySelectorAll('.question-item').forEach(item => {
        const inputs = item.querySelectorAll('input:checked, input[type="text"]:not([value=""]), select');
        let hasAnswer = false;
        inputs.forEach(inp => {
            if (inp.type === 'radio' && inp.checked) hasAnswer = true;
            if (inp.type === 'checkbox' && inp.checked) hasAnswer = true;
            if (inp.type === 'text' && inp.value.trim()) hasAnswer = true;
            if (inp.tagName === 'SELECT' && inp.value) hasAnswer = true;
        });
        if (hasAnswer) count++;
    });
    document.getElementById('answered-count').textContent = count;
}

</script>
@endpush
@endsection