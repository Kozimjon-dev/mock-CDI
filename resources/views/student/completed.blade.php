@extends('layouts.app')

@section('title', 'Test Completed')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Test Completed!</h1>
            <p class="mt-2 text-gray-600">Congratulations, {{ $session->student->full_name }}!</p>
        </div>

        <!-- Overall Band Score -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Overall Band Score</p>
            <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-indigo-600 text-white mb-3">
                <span class="text-4xl font-bold">{{ number_format($scores['overall']['band'], 1) }}</span>
            </div>
            <p class="text-gray-500 text-sm">{{ $scores['overall']['correct'] }}/{{ $scores['overall']['total'] }} correct answers</p>
        </div>

        <!-- Module Band Scores -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <!-- Listening -->
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <div class="flex items-center justify-center mb-3">
                    <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6a7 7 0 017 7M8.464 15.536a5 5 0 010-7.072M12 18a7 7 0 01-7-7" />
                    </svg>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Listening</h3>
                </div>
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-purple-100 text-purple-700 mb-2">
                    <span class="text-3xl font-bold">{{ number_format($scores['listening']['band'], 1) }}</span>
                </div>
                <p class="text-sm text-gray-500">{{ $scores['listening']['correct'] }}/{{ $scores['listening']['total'] }} correct
                    @if($scores['listening']['total'] > 0)
                        ({{ round(($scores['listening']['correct'] / $scores['listening']['total']) * 100) }}%)
                    @endif
                </p>
            </div>

            <!-- Reading -->
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <div class="flex items-center justify-center mb-3">
                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Reading</h3>
                </div>
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 text-blue-700 mb-2">
                    <span class="text-3xl font-bold">{{ number_format($scores['reading']['band'], 1) }}</span>
                </div>
                <p class="text-sm text-gray-500">{{ $scores['reading']['correct'] }}/{{ $scores['reading']['total'] }} correct
                    @if($scores['reading']['total'] > 0)
                        ({{ round(($scores['reading']['correct'] / $scores['reading']['total']) * 100) }}%)
                    @endif
                </p>
            </div>
        </div>

        <!-- Writing Notice -->
        <div class="bg-green-50 rounded-xl shadow p-6 text-center mb-6">
            <div class="flex items-center justify-center mb-2">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-500 uppercase">Writing</h3>
            </div>
            <p class="text-sm text-green-700 font-medium">Submitted — will be reviewed by your instructor</p>
        </div>

        <!-- Chart -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Score Breakdown</h3>
            <div class="max-w-sm mx-auto">
                <canvas id="scoreChart" height="220"></canvas>
            </div>
        </div>

        <!-- Badges Section -->
        @if(!empty($badges ?? []))
        @php
            $badgeStyles = [
                'blue'   => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'text' => 'text-blue-700'],
                'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-700'],
                'yellow' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700'],
                'amber'  => ['bg' => 'bg-amber-50',  'border' => 'border-amber-200',  'text' => 'text-amber-700'],
                'green'  => ['bg' => 'bg-green-50',  'border' => 'border-green-200',  'text' => 'text-green-700'],
                'indigo' => ['bg' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'text' => 'text-indigo-700'],
            ];
        @endphp
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Achievements Earned</h3>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach($badges as $badge)
                @php $bs = $badgeStyles[$badge['color']] ?? ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'text' => 'text-gray-700']; @endphp
                <div class="flex items-center {{ $bs['bg'] }} border {{ $bs['border'] }} rounded-full px-4 py-2">
                    <span class="text-lg mr-2">{{ $badge['icon'] }}</span>
                    <span class="text-sm font-medium {{ $bs['text'] }}">{{ $badge['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Test Info -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Test:</span>
                    <p class="font-medium text-gray-900">{{ $session->test->title }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Completed:</span>
                    <p class="font-medium text-gray-900">{{ $session->completed_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
            <a href="{{ route('student.session.review', $session->session_token) }}"
               class="flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Review Answers
            </a>
            <a href="{{ route('student.history') }}"
               class="flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Test History
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('home') }}"
               class="flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                Return to Home
            </a>
            <a href="{{ route('tests') }}"
               class="flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                View Available Tests
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Band score chart
const ctx = document.getElementById('scoreChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Listening', 'Reading', 'Overall'],
        datasets: [{
            label: 'Band Score',
            data: [
                {{ $scores['listening']['band'] }},
                {{ $scores['reading']['band'] }},
                {{ $scores['overall']['band'] }}
            ],
            backgroundColor: [
                'rgba(147, 51, 234, 0.7)',
                'rgba(59, 130, 246, 0.7)',
                'rgba(79, 70, 229, 0.8)'
            ],
            borderColor: [
                'rgba(147, 51, 234, 1)',
                'rgba(59, 130, 246, 1)',
                'rgba(79, 70, 229, 1)'
            ],
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 9,
                ticks: {
                    stepSize: 1
                },
                title: {
                    display: true,
                    text: 'Band Score'
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Band ' + context.parsed.y.toFixed(1);
                    }
                }
            }
        }
    }
});

// Prevent going back to test pages
window.history.pushState(null, null, window.location.href);
window.addEventListener('popstate', function() {
    window.history.pushState(null, null, window.location.href);
});
</script>
@endpush
@endsection
