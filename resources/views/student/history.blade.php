@extends('layouts.app')

@section('title', 'Test History')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Test History</h1>

        <!-- Phone Number Lookup -->
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <form method="GET" action="{{ route('student.history') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Enter your phone number to view results</label>
                    <input type="text" name="phone" id="phone" value="{{ $phone ?? '' }}"
                           placeholder="+998 90 123 45 67"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2.5 border">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        Search
                    </button>
                </div>
            </form>
        </div>

        @if($phone)
            @if(!$student)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <p class="text-yellow-800 text-sm">No student found with phone number <strong>{{ $phone }}</strong>. Please check and try again.</p>
                </div>
            @elseif($sessions->isEmpty())
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-blue-800 text-sm">No completed tests found for <strong>{{ $student->full_name }}</strong>.</p>
                </div>
            @else
                <!-- Student Info -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $student->full_name }}</h2>
                        <p class="text-sm text-gray-500">{{ $sessions->count() }} completed test{{ $sessions->count() !== 1 ? 's' : '' }}</p>
                    </div>
                </div>

                <!-- Badges -->
                @php
                    $badges = [];
                    if ($sessions->count() >= 1) $badges[] = ['icon' => '🎯', 'name' => 'First Test', 'color' => 'blue'];
                    if ($sessions->count() >= 3) $badges[] = ['icon' => '🔁', 'name' => 'Consistent', 'color' => 'purple'];

                    $bestOverall = $sessions->max(fn($s) => $s->module_scores['overall']['band']);
                    if ($bestOverall >= 6.0) $badges[] = ['icon' => '⭐', 'name' => 'Band 6+', 'color' => 'yellow'];
                    if ($bestOverall >= 7.0) $badges[] = ['icon' => '🏆', 'name' => 'Band 7+', 'color' => 'amber'];

                    $hasPerfectListening = $sessions->contains(fn($s) => $s->module_scores['listening']['total'] > 0 && $s->module_scores['listening']['correct'] === $s->module_scores['listening']['total']);
                    $hasPerfectReading = $sessions->contains(fn($s) => $s->module_scores['reading']['total'] > 0 && $s->module_scores['reading']['correct'] === $s->module_scores['reading']['total']);
                    if ($hasPerfectListening) $badges[] = ['icon' => '🎧', 'name' => 'Perfect Listener', 'color' => 'green'];
                    if ($hasPerfectReading) $badges[] = ['icon' => '📖', 'name' => 'Perfect Reader', 'color' => 'green'];

                    if ($sessions->count() >= 2) {
                        $oldest = $sessions->last()->module_scores['overall']['band'];
                        $newest = $sessions->first()->module_scores['overall']['band'];
                        if ($newest > $oldest) $badges[] = ['icon' => '📈', 'name' => 'Improving', 'color' => 'indigo'];
                    }
                @endphp

                @if(count($badges) > 0)
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
                <div class="bg-white rounded-xl shadow p-5 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Achievements</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($badges as $badge)
                        @php $bs = $badgeStyles[$badge['color']] ?? ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'text' => 'text-gray-700']; @endphp
                        <span class="inline-flex items-center {{ $bs['bg'] }} border {{ $bs['border'] }} rounded-full px-3 py-1.5 text-sm">
                            <span class="mr-1.5">{{ $badge['icon'] }}</span>
                            <span class="font-medium {{ $bs['text'] }}">{{ $badge['name'] }}</span>
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Progress Chart -->
                @if($sessions->count() >= 2)
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Band Score Progress</h3>
                    <canvas id="progressChart" height="200"></canvas>
                </div>
                @endif

                <!-- Sessions Table -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Listening</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Reading</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Overall</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($sessions as $s)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $s->test->title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $s->completed_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-700 text-sm font-bold">
                                            {{ number_format($s->module_scores['listening']['band'], 1) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">
                                            {{ number_format($s->module_scores['reading']['band'], 1) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 text-sm font-bold">
                                            {{ number_format($s->module_scores['overall']['band'], 1) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('student.session.review', $s->session_token) }}"
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>

@if(!empty($sessions) && $sessions->count() >= 2)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const sessionsData = @json($sessions->reverse()->values()->map(fn($s) => [
    'date' => $s->completed_at->format('M d'),
    'listening' => $s->module_scores['listening']['band'],
    'reading' => $s->module_scores['reading']['band'],
    'overall' => $s->module_scores['overall']['band'],
]));

new Chart(document.getElementById('progressChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: sessionsData.map(s => s.date),
        datasets: [
            {
                label: 'Listening',
                data: sessionsData.map(s => s.listening),
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.3,
                fill: false,
            },
            {
                label: 'Reading',
                data: sessionsData.map(s => s.reading),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: false,
            },
            {
                label: 'Overall',
                data: sessionsData.map(s => s.overall),
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: false,
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                min: 0,
                max: 9,
                ticks: { stepSize: 1 },
                title: { display: true, text: 'Band Score' }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Band ' + context.parsed.y.toFixed(1);
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endif
@endsection
