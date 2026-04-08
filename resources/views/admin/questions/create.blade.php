@extends('layouts.admin')

@section('title', 'Add Question')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Question</h1>
                <p class="mt-2 text-gray-600">Add a new question to "{{ $test->title }}"</p>
            </div>
            <a href="{{ route('admin.tests.show', $test) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Test
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.questions.store') }}" method="POST" id="questionForm">
            @csrf
            <input type="hidden" name="test_id" value="{{ $test->id }}">
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Question Details</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Module Selection -->
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700">Module *</label>
                    <select name="module" id="module" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('module') border-red-300 @enderror">
                        <option value="">Select Module</option>
                        <option value="listening" {{ old('module') == 'listening' ? 'selected' : '' }}>Listening</option>
                        <option value="reading" {{ old('module') == 'reading' ? 'selected' : '' }}>Reading</option>
                        <option value="writing" {{ old('module') == 'writing' ? 'selected' : '' }}>Writing</option>
                    </select>
                    @error('module')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Part Selection -->
                <div>
                    <label for="part" class="block text-sm font-medium text-gray-700">Part *</label>
                    <select name="part" id="part" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('part') border-red-300 @enderror">
                        <option value="">Select Part</option>
                        <option value="1" {{ old('part') == '1' ? 'selected' : '' }}>Part 1</option>
                        <option value="2" {{ old('part') == '2' ? 'selected' : '' }}>Part 2</option>
                        <option value="3" {{ old('part') == '3' ? 'selected' : '' }}>Part 3</option>
                        <option value="4" {{ old('part') == '4' ? 'selected' : '' }}>Part 4</option>
                    </select>
                    @error('part')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Question Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Question Type *</label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('type') border-red-300 @enderror">
                        <option value="">Select Type</option>
                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                        <option value="gap_filling" {{ old('type') == 'gap_filling' ? 'selected' : '' }}>Gap Filling</option>
                        <option value="select_options" {{ old('type') == 'select_options' ? 'selected' : '' }}>Select Options</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Question Text -->
                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700">Question Text *</label>
                    <textarea name="question_text" id="question_text" rows="3" required
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('question_text') border-red-300 @enderror"
                              placeholder="Enter the question text...">{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options (for multiple choice and select options) -->
                <div id="optionsSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Options *</label>
                    <div id="optionsContainer" class="mt-2 space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="options[]" 
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Option 1">
                            <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addOption()" 
                            class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Option
                    </button>
                </div>

                <!-- Correct Answers -->
                <div id="correctAnswersSection">
                    <label class="block text-sm font-medium text-gray-700">Correct Answer(s) *</label>
                    <div id="correctAnswersContainer" class="mt-2 space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="correct_answers[]" required
                                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Correct answer">
                            <button type="button" onclick="removeCorrectAnswer(this)" class="text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addCorrectAnswer()" 
                            class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Answer
                    </button>
                </div>

                <!-- Points -->
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700">Points *</label>
                    <input type="number" name="points" id="points" required min="1" max="10"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('points') border-red-300 @enderror"
                           value="{{ old('points', 1) }}">
                    @error('points')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="order" id="order" min="1"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('order') border-red-300 @enderror"
                           value="{{ old('order', 1) }}">
                    <p class="mt-1 text-sm text-gray-500">Question order within the part</p>
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.show', $test) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Question
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const optionsSection = document.getElementById('optionsSection');
    
    typeSelect.addEventListener('change', function() {
        if (this.value === 'multiple_choice' || this.value === 'select_options') {
            optionsSection.classList.remove('hidden');
        } else {
            optionsSection.classList.add('hidden');
        }
    });
});

function addOption() {
    const container = document.getElementById('optionsContainer');
    const optionCount = container.children.length + 1;
    
    const optionDiv = document.createElement('div');
    optionDiv.className = 'flex items-center space-x-2';
    optionDiv.innerHTML = `
        <input type="text" name="options[]" 
               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
               placeholder="Option ${optionCount}">
        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-900">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    `;
    
    container.appendChild(optionDiv);
}

function removeOption(button) {
    button.parentElement.remove();
}

function addCorrectAnswer() {
    const container = document.getElementById('correctAnswersContainer');
    const answerCount = container.children.length + 1;
    
    const answerDiv = document.createElement('div');
    answerDiv.className = 'flex items-center space-x-2';
    answerDiv.innerHTML = `
        <input type="text" name="correct_answers[]" required
               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
               placeholder="Correct answer ${answerCount}">
        <button type="button" onclick="removeCorrectAnswer(this)" class="text-red-600 hover:text-red-900">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    `;
    
    container.appendChild(answerDiv);
}

function removeCorrectAnswer(button) {
    if (document.getElementById('correctAnswersContainer').children.length > 1) {
        button.parentElement.remove();
    }
}
</script>
@endpush
@endsection 