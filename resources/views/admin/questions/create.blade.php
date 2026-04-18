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
        <form action="{{ route('admin.questions.store') }}" method="POST" id="questionForm" enctype="multipart/form-data">
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
                        @foreach(\App\Models\Question::TYPE_LABELS as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <!-- Type description hint -->
                    <p id="type-hint" class="mt-1 text-sm text-gray-500 hidden"></p>
                </div>

                <!-- Question Text -->
                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700">Question Text *</label>
                    <textarea name="question_text" id="question_text" rows="3" required
                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('question_text') border-red-300 @enderror"
                              placeholder="Enter the question text...">{{ old('question_text') }}</textarea>
                    <p id="question-text-hint" class="mt-1 text-sm text-gray-500 hidden"></p>
                    @error('question_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options (for multiple choice, select options, sentence completion, matching right-side, diagram word bank) -->
                <div id="optionsSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700"><span id="options-label">Options</span> *</label>
                    <p id="options-hint" class="text-sm text-gray-500 mb-2"></p>
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

                <!-- Matching Items Section -->
                <div id="matchingSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Matching Pairs *</label>
                    <p class="text-sm text-gray-500 mb-2">Enter left items in Correct Answers and right items in Options. correct_answers[0] matches options[0], etc.</p>
                </div>

                <!-- Image URL (for diagram labeling and writing tasks) -->
                <div id="imageSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Image URL (optional)</label>
                    <input type="text" name="metadata[image_url]"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="https://example.com/image.png"
                           value="{{ old('metadata.image_url') }}">
                    <p id="image-hint" class="mt-1 text-sm text-gray-500">Image will be displayed to the student alongside the question.</p>
                </div>

                <!-- Correct Answer (hidden for ordering, shown for all others) -->
                <div id="correctAnswersSection">
                    <label for="correct_answer" class="block text-sm font-medium text-gray-700"><span id="answers-label">Correct Answer</span> *</label>
                    <p id="answers-hint" class="text-sm text-gray-500 mb-2 hidden"></p>
                    <input type="text" name="correct_answers[]" id="correct_answer" required
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Correct answer" value="{{ old('correct_answers.0') }}">
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
const typeConfig = {
    multiple_choice: {
        showOptions: true,
        showCorrectAnswers: true,
        optionsLabel: 'Options',
        optionsHint: 'Add at least 2 options for the student to choose from.',
        answersLabel: 'Correct Answer',
        answersHint: 'Enter the exact text of the correct option.',
        questionHint: '',
        showMatching: false,
        showImage: false,
    },
    gap_filling: {
        showOptions: false,
        showCorrectAnswers: true,
        answersLabel: 'Correct Answer(s)',
        answersHint: 'Enter each blank answer. Order must match the blanks in the question.',
        questionHint: 'Use _____ to indicate blanks in the question text.',
        showMatching: false,
        showImage: false,
    },
    true_false_notgiven: {
        showOptions: false,
        showCorrectAnswers: true,
        answersLabel: 'Correct Answer',
        answersHint: 'Enter exactly: True, False, or Not Given',
        questionHint: 'Enter the statement that students will evaluate.',
        showMatching: false,
        showImage: false,
    },
    yes_no_notgiven: {
        showOptions: false,
        showCorrectAnswers: true,
        answersLabel: 'Correct Answer',
        answersHint: 'Enter exactly: Yes, No, or Not Given',
        questionHint: 'Enter the statement that students will evaluate.',
        showMatching: false,
        showImage: false,
    },
    matching: {
        showOptions: true,
        showCorrectAnswers: true,
        optionsLabel: 'Right Column Items (to match from)',
        optionsHint: 'These are the items students will choose from (e.g., descriptions, categories).',
        answersLabel: 'Left Column Items = Correct Matches',
        answersHint: 'Enter left-side items. Answer 1 matches Option 1, Answer 2 matches Option 2, etc.',
        questionHint: 'Describe the matching task (e.g., "Match each researcher with their finding").',
        showMatching: true,
        showImage: false,
    },
    sentence_completion: {
        showOptions: true,
        showCorrectAnswers: true,
        optionsLabel: 'Word Bank',
        optionsHint: 'Add the words/phrases students will choose from to fill the blanks.',
        answersLabel: 'Correct Answers (in blank order)',
        answersHint: 'Enter correct word/phrase for each blank, in order.',
        questionHint: 'Use _____ to indicate blanks. E.g., "The _____ was discovered in _____."',
        showMatching: false,
        showImage: false,
    },
    short_answer: {
        showOptions: false,
        showCorrectAnswers: true,
        answersLabel: 'Accepted Answers',
        answersHint: 'Enter all accepted answer variations (e.g., "solar energy", "solar power").',
        questionHint: 'Students will type a short answer (max 3 words).',
        showMatching: false,
        showImage: false,
    },
    diagram_labeling: {
        showOptions: true,
        showCorrectAnswers: true,
        optionsLabel: 'Word Bank',
        optionsHint: 'Words/phrases students choose from to label the diagram.',
        answersLabel: 'Correct Labels (position order)',
        answersHint: 'Enter correct label for each position (1, 2, 3...) in order.',
        questionHint: 'Describe what the diagram shows.',
        showMatching: false,
        showImage: true,
    },
};

document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    typeSelect.addEventListener('change', updateFormForType);
    document.getElementById('module').addEventListener('change', updateFormForType);
    if (typeSelect.value) updateFormForType();
});

function updateFormForType() {
    const type = document.getElementById('type').value;
    const config = typeConfig[type];
    if (!config) return;

    // Options section
    const optionsSection = document.getElementById('optionsSection');
    optionsSection.classList.toggle('hidden', !config.showOptions);
    if (config.optionsLabel) document.getElementById('options-label').textContent = config.optionsLabel;
    if (config.optionsHint) {
        document.getElementById('options-hint').textContent = config.optionsHint;
        document.getElementById('options-hint').classList.remove('hidden');
    }

    // Correct answers section
    const answersSection = document.getElementById('correctAnswersSection');
    answersSection.classList.toggle('hidden', !config.showCorrectAnswers);
    if (config.answersLabel) document.getElementById('answers-label').textContent = config.answersLabel;
    const answersHint = document.getElementById('answers-hint');
    if (config.answersHint) {
        answersHint.textContent = config.answersHint;
        answersHint.classList.remove('hidden');
    } else {
        answersHint.classList.add('hidden');
    }

    // Question text hint
    const questionHint = document.getElementById('question-text-hint');
    if (config.questionHint) {
        questionHint.textContent = config.questionHint;
        questionHint.classList.remove('hidden');
    } else {
        questionHint.classList.add('hidden');
    }

    // Matching hint
    document.getElementById('matchingSection').classList.toggle('hidden', !config.showMatching);

    // Image section (diagram / writing task image)
    const module = document.getElementById('module').value;
    const showImage = config.showImage || module === 'writing';
    document.getElementById('imageSection').classList.toggle('hidden', !showImage);
}

function addOption() {
    const container = document.getElementById('optionsContainer');
    const count = container.children.length + 1;
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="options[]"
               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
               placeholder="Option ${count}">
        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-900">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeOption(button) {
    button.parentElement.remove();
}

</script>
@endpush
@endsection