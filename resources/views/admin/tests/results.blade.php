@extends('layouts.admin')

@section('title', 'Test Results')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Results: {{ $test->title }}</h2>
        <p class="mt-1 text-gray-600">Performance analytics for completed test sessions.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Total Sessions</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_sessions'] }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_students'] }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Avg Listening Score</dt>
            <dd class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['avg_listening_score'] }}%</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Avg Reading Score</dt>
            <dd class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['avg_reading_score'] }}%</dd>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Completed Sessions</h3>
        </div>

        @if($sessions->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Listening</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reading</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cheating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($sessions as $session)
                @php
                    $listeningCorrect = $session->studentResponses->where('module', 'listening')->where('is_correct', true)->count();
                    $listeningTotal = $test->listeningQuestions()->count();
                    $readingCorrect = $session->studentResponses->where('module', 'reading')->where('is_correct', true)->count();
                    $readingTotal = $test->readingQuestions()->count();
                    $totalCorrect = $listeningCorrect + $readingCorrect;
                    $totalQuestions = $listeningTotal + $readingTotal;
                @endphp
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $session->student->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $session->student->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="{{ $listeningTotal > 0 && ($listeningCorrect / $listeningTotal) >= 0.7 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $listeningCorrect }}/{{ $listeningTotal }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="{{ $readingTotal > 0 && ($readingCorrect / $readingTotal) >= 0.7 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $readingCorrect }}/{{ $readingTotal }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $totalCorrect }}/{{ $totalQuestions }}
                        @if($totalQuestions > 0)
                            ({{ round(($totalCorrect / $totalQuestions) * 100) }}%)
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($session->has_cheated)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Detected</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Clean</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->completed_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($sessions->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $sessions->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No completed sessions yet.</p>
        </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.tests.show', $test) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to Test</a>
    </div>
</div>
@endsection
