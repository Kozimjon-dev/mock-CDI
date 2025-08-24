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
            <div class="flex items-center space-x-4">
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

    <div class="flex h-screen">
        <!-- Audio Player Section -->
        <div class="w-1/3 bg-gray-800 p-6 border-r border-gray-700">
            <div class="mb-6">
                <h2 class="text-lg font-bold mb-4">Audio Player</h2>
                @if($audioMaterial)
                <div class="bg-gray-700 rounded-lg p-4">
                    <div class="mb-4">
                        <div class="text-sm text-gray-400 mb-2">Audio File</div>
                        <div class="text-white font-medium">{{ $audioMaterial->title }}</div>
                    </div>
                    
                    <!-- Hidden audio element without controls -->
                    <audio id="audio-player" preload="auto" style="display: none;">
                        <source src="{{ $audioMaterial->file_url }}" type="{{ $audioMaterial->mime_type }}">
                        Your browser does not support the audio element.
                    </audio>
                    
                    <!-- Custom secure audio controls -->
                    <div class="bg-gray-600 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-300">Audio Progress</span>
                            <span id="audio-time" class="text-sm text-gray-300">0:00 / 0:00</span>
                        </div>
                        
                        <!-- Progress bar (read-only) -->
                        <div class="w-full bg-gray-500 rounded-full h-2 mb-3">
                            <div id="audio-progress" class="bg-green-500 h-2 rounded-full transition-all duration-100" style="width: 0%"></div>
                        </div>
                        
                        <!-- Custom controls -->
                        <div class="flex justify-center space-x-4">
                            <button id="start-audio" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                                Start Audio
                            </button>
                            <button id="stop-audio" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-medium hidden">
                                Stop Audio
                            </button>
                        </div>
                        
                        <!-- Audio status -->
                        <div class="text-center mt-3">
                            <span id="audio-status" class="text-sm text-gray-300">Ready to start</span>
                        </div>
                    </div>
                    
                    <!-- Security notice -->
                    <div class="bg-red-900 rounded-lg p-3">
                        <div class="text-red-200 text-sm">
                            <strong>⚠️ Security Notice:</strong> Audio cannot be paused or replayed. Once started, it will play completely.
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-red-900 rounded-lg p-4">
                    <div class="text-red-200">No audio file available</div>
                </div>
                @endif
            </div>

            <!-- Part Navigation -->
            <div class="mb-6">
                <h3 class="text-md font-bold mb-3">Parts</h3>
                <div class="space-y-2">
                    @foreach($questions as $part => $partQuestions)
                    <button class="part-nav w-full text-left px-3 py-2 rounded {{ $loop->first ? 'bg-indigo-600' : 'bg-gray-700 hover:bg-gray-600' }}" 
                            data-part="{{ $part }}">
                        Part {{ $part }} ({{ $partQuestions->count() }} questions)
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-900 rounded-lg p-4">
                <h3 class="text-md font-bold mb-2">Instructions</h3>
                <ul class="text-sm text-blue-200 space-y-1">
                    <li>• Listen to the audio carefully</li>
                    <li>• Answer questions as you listen</li>
                    <li>• <strong>You cannot pause or replay the audio</strong></li>
                    <li>• Complete all parts within the time limit</li>
                </ul>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="w-2/3 bg-gray-900 p-6 overflow-y-auto">
            <div id="questions-container">
                @foreach($questions as $part => $partQuestions)
                <div class="part-questions {{ $loop->first ? '' : 'hidden' }}" data-part="{{ $part }}">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold mb-4">Part {{ $part }}</h2>
                        <p class="text-gray-400 mb-6">Answer the following {{ $partQuestions->count() }} questions based on the audio.</p>
                    </div>

                    <div class="space-y-8">
                        @foreach($partQuestions as $question)
                        <div class="bg-gray-800 rounded-lg p-6 question-item" data-question-id="{{ $question->id }}">
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium">Question {{ $loop->index + 1 }}</h3>
                                    <span class="text-sm text-gray-400">{{ $question->points }} point(s)</span>
                                </div>
                                <p class="text-gray-300 mt-2">{{ $question->question_text }}</p>
                            </div>

                            <div class="question-options">
                                @if($question->isMultipleChoice())
                                    <div class="space-y-3">
                                        @foreach($question->options_array as $index => $option)
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}" 
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="text-gray-300">{{ chr(65 + $index) }}. {{ $option }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                @elseif($question->isGapFilling())
                                    <div class="space-y-3">
                                        @foreach($question->correct_answers_array as $index => $answer)
                                        <div class="flex items-center space-x-3">
                                            <span class="text-gray-300">{{ $index + 1 }}.</span>
                                            <input type="text" name="question_{{ $question->id }}[]" 
                                                   class="flex-1 bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:ring-indigo-500 focus:border-indigo-500"
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
                                            <span class="text-gray-300">{{ $option }}</span>
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
            <div class="mt-8 pt-6 border-t border-gray-700">
                <div class="flex justify-between items-center">
                    <button id="prev-part" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
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
/* Full-screen styles */
#listening-test {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
}

/* Prevent text selection */
* {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Allow selection in input fields */
input, textarea {
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}

/* Disable audio element interactions */
#audio-player {
    pointer-events: none;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
</style>
@endpush

@push('scripts')
<script>
let currentPart = 1;
let totalParts = {{ $questions->count() }};
let timeRemaining = {{ $session->test->listening_time * 60 }};
let timerInterval;
let audioStarted = false;
let audioDuration = 0;
let audioProgressInterval;

// Initialize the test
document.addEventListener('DOMContentLoaded', function() {
    initializeFullscreen();
    startTimer();
    initializePartNavigation();
    initializeSecureAudioPlayer();
    setupAntiCheating();
    startHeartbeat();
});

function initializeFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            console.log('Fullscreen request failed');
        });
    }
}

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

function initializePartNavigation() {
    document.getElementById('prev-part').addEventListener('click', function() {
        if (currentPart > 1) {
            showPart(currentPart - 1);
        }
    });
    
    document.getElementById('next-part').addEventListener('click', function() {
        if (currentPart < totalParts) {
            showPart(currentPart + 1);
        }
    });
    
    // Part navigation buttons
    document.querySelectorAll('.part-nav').forEach(button => {
        button.addEventListener('click', function() {
            const part = parseInt(this.dataset.part);
            showPart(part);
        });
    });
}

function showPart(part) {
    // Hide all parts
    document.querySelectorAll('.part-questions').forEach(el => el.classList.add('hidden'));
    
    // Show selected part
    document.querySelector(`[data-part="${part}"]`).classList.remove('hidden');
    
    // Update navigation
    document.querySelectorAll('.part-nav').forEach(btn => {
        btn.classList.remove('bg-indigo-600');
        btn.classList.add('bg-gray-700');
    });
    document.querySelector(`[data-part="${part}"]`).classList.remove('bg-gray-700');
    document.querySelector(`[data-part="${part}"]`).classList.add('bg-indigo-600');
    
    // Update current part display
    currentPart = part;
    document.getElementById('current-part').textContent = part;
    
    // Update navigation buttons
    document.getElementById('prev-part').disabled = part === 1;
    document.getElementById('next-part').disabled = part === totalParts;
}

function initializeSecureAudioPlayer() {
    const audioPlayer = document.getElementById('audio-player');
    const startButton = document.getElementById('start-audio');
    const stopButton = document.getElementById('stop-audio');
    const progressBar = document.getElementById('audio-progress');
    const audioTime = document.getElementById('audio-time');
    const audioStatus = document.getElementById('audio-status');
    
    // Get audio duration when metadata is loaded
    audioPlayer.addEventListener('loadedmetadata', function() {
        audioDuration = audioPlayer.duration;
        updateAudioTime(0, audioDuration);
    });
    
    // Start button functionality
    if (startButton) {
        startButton.addEventListener('click', function() {
            if (!audioStarted) {
                audioPlayer.play().then(() => {
                    audioStarted = true;
                    startButton.classList.add('hidden');
                    stopButton.classList.remove('hidden');
                    audioStatus.textContent = 'Playing...';
                    audioStatus.className = 'text-sm text-green-400';
                    
                    // Start progress tracking
                    audioProgressInterval = setInterval(updateAudioProgress, 100);
                    
                    // Record that audio was started
                    recordCheatAttempt('Audio started');
                }).catch(error => {
                    console.error('Audio playback failed:', error);
                    audioStatus.textContent = 'Failed to start audio';
                    audioStatus.className = 'text-sm text-red-400';
                });
            }
        });
    }
    
    // Stop button functionality (only stops, cannot restart)
    if (stopButton) {
        stopButton.addEventListener('click', function() {
            audioPlayer.pause();
            audioPlayer.currentTime = 0;
            audioStarted = true; // Keep as true to prevent restart
            startButton.classList.remove('hidden');
            stopButton.classList.add('hidden');
            startButton.disabled = true;
            startButton.textContent = 'Audio Completed';
            startButton.className = 'bg-gray-600 text-white px-6 py-2 rounded-md font-medium cursor-not-allowed';
            audioStatus.textContent = 'Audio stopped - cannot restart';
            audioStatus.className = 'text-sm text-red-400';
            
            clearInterval(audioProgressInterval);
            updateAudioProgress();
            
            recordCheatAttempt('Audio stopped by user');
        });
    }
    
    // Audio ended event
    audioPlayer.addEventListener('ended', function() {
        audioStarted = true; // Prevent restart
        startButton.classList.remove('hidden');
        stopButton.classList.add('hidden');
        startButton.disabled = true;
        startButton.textContent = 'Audio Completed';
        startButton.className = 'bg-gray-600 text-white px-6 py-2 rounded-md font-medium cursor-not-allowed';
        audioStatus.textContent = 'Audio playback completed';
        audioStatus.className = 'text-sm text-green-400';
        
        clearInterval(audioProgressInterval);
        updateAudioProgress();
    });
    
    // Prevent all audio controls
    audioPlayer.addEventListener('pause', function(e) {
        if (audioStarted && !audioPlayer.ended) {
            e.preventDefault();
            audioPlayer.play(); // Force continue playing
            recordCheatAttempt('Attempted to pause audio');
        }
    });
    
    audioPlayer.addEventListener('seeked', function(e) {
        e.preventDefault();
        recordCheatAttempt('Attempted to seek audio');
    });
    
    // Disable right-click on audio
    audioPlayer.addEventListener('contextmenu', e => e.preventDefault());
    audioPlayer.addEventListener('selectstart', e => e.preventDefault());
    
    // Disable keyboard shortcuts for audio
    document.addEventListener('keydown', function(e) {
        if (audioStarted && (e.key === ' ' || e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
            e.preventDefault();
            recordCheatAttempt('Audio control keyboard shortcut attempted');
        }
    });
}

function updateAudioProgress() {
    const audioPlayer = document.getElementById('audio-player');
    const progressBar = document.getElementById('audio-progress');
    const audioTime = document.getElementById('audio-time');
    
    if (audioPlayer.duration && !isNaN(audioPlayer.duration)) {
        const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
        progressBar.style.width = progress + '%';
        updateAudioTime(audioPlayer.currentTime, audioPlayer.duration);
    }
}

function updateAudioTime(current, total) {
    const audioTime = document.getElementById('audio-time');
    const currentFormatted = formatTime(current);
    const totalFormatted = formatTime(total);
    audioTime.textContent = `${currentFormatted} / ${totalFormatted}`;
}

function formatTime(seconds) {
    if (isNaN(seconds)) return '0:00';
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
}

function setupAntiCheating() {
    // Prevent keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x' || e.key === 'a' || e.key === 'z' || e.key === 'y' || e.key === 's' || e.key === 'p')) || 
            e.key === 'F5' || 
            (e.ctrlKey && e.key === 'r') ||
            e.key === 'Escape') {
            e.preventDefault();
            return false;
        }
    });
    
    // Prevent right-click
    document.addEventListener('contextmenu', e => e.preventDefault());
    
    // Prevent text selection
    document.addEventListener('selectstart', e => e.preventDefault());
    
    // Prevent drag and drop
    document.addEventListener('dragstart', e => e.preventDefault());
    
    // Monitor fullscreen changes
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            recordCheatAttempt('Exited fullscreen mode');
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
                fullscreen: !!document.fullscreenElement,
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
            fullscreen: !!document.fullscreenElement,
            focused: !document.hidden,
            right_click: attempt.includes('right-click'),
            keyboard_shortcut: attempt.includes('keyboard') || attempt.includes('shortcut'),
            tab_switch: attempt.includes('tab')
        })
    });
}

function completeModule() {
    clearInterval(timerInterval);
    clearInterval(audioProgressInterval);
    
    fetch('{{ route("student.session.complete-module", $session->session_token) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            module: 'listening'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_completed) {
                window.location.href = '{{ route("student.session.show", $session->session_token) }}';
            } else {
                window.location.href = '{{ route("student.session.reading", $session->session_token) }}';
            }
        }
    });
}

// Complete module button
document.getElementById('complete-module').addEventListener('click', function() {
    if (confirm('Are you sure you want to complete the Listening module? You cannot return to it later.')) {
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