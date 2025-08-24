@extends('layouts.app')

@section('title', 'Test Completed')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Test Completed!</h2>
            <p class="text-gray-600 mb-6">
                Congratulations! You have successfully completed the {{ $session->test->title }}.
            </p>
            
            <!-- Test Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Student:</span>
                        <p class="font-medium text-gray-900">{{ $session->student->full_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Session ID:</span>
                        <p class="font-medium text-gray-900">{{ $session->session_token }}</p>
                    </div>
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
            
            <!-- Module Completion Status -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Module Completion</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Listening</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Reading</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Writing</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Important Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Important Notice</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Your test has been submitted successfully. Results will be available once reviewed by your instructor.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('home') }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Return to Home
                </a>
                
                <a href="{{ route('tests') }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View Available Tests
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Prevent going back to test pages
window.history.pushState(null, null, window.location.href);
window.addEventListener('popstate', function() {
    window.history.pushState(null, null, window.location.href);
});

// Disable keyboard shortcuts
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
</script>
@endpush
@endsection
