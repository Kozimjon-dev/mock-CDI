@extends('layouts.admin')

@section('title', 'Test Sessions')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Sessions: {{ $test->title }}</h2>
        <p class="mt-1 text-gray-600">All test sessions (active and completed).</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($sessions->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cheating</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($sessions as $session)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $session->student->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $session->student->email ?? $session->student->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $session->current_module === 'completed' ? 'bg-green-100 text-green-800' :
                               ($session->current_module === 'dashboard' ? 'bg-gray-100 text-gray-800' :
                               ($session->current_module === 'listening' ? 'bg-purple-100 text-purple-800' :
                               ($session->current_module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                            {{ ucfirst($session->current_module) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($session->completed_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In Progress</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($session->has_cheated)
                            @php $cheats = json_decode($session->cheat_attempts, true) ?? []; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ count($cheats) }} attempt(s)</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Clean</span>
                        @endif
                    </td>
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
            <p class="text-sm text-gray-500">No sessions found for this test.</p>
        </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.tests.show', $test) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to Test</a>
    </div>
</div>
@endsection
