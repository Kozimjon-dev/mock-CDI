@extends('layouts.admin')

@section('title', 'Edit Question')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Edit Question</h2>
        <p class="mt-1 text-gray-600">From: {{ $question->test->title }}</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.questions.update', $question) }}" method="POST" id="questionForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 space-y-6">
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700">Module *</label>
                    <select name="module" id="module" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="listening" {{ old('module', $question->module) == 'listening' ? 'selected' : '' }}>Listening</option>
                        <option value="reading" {{ old('module', $question->module) == 'reading' ? 'selected' : '' }}>Reading</option>
                        <option value="writing" {{ old('module', $question->module) == 'writing' ? 'selected' : '' }}>Writing</option>
                    </select>
                    @error('module') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="part" class="block text-sm font-medium text-gray-700">Part *</label>
                    <select name="part" id="part" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ old('part', $question->part) == $i ? 'selected' : '' }}>Part {{ $i }}</option>
                        @endfor
                    </select>
                    @error('part') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                    <select name="type" id="type" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(\App\Models\Question::TYPE_LABELS as $value => $label)
                            <option value="{{ $value }}" {{ old('type', $question->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p id="type-hint" class="mt-1 text-sm text-gray-500 hidden"></p>
                </div>

                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700">Question Text *</label>
                    <textarea name="question_text" id="question_text" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('question_text', $question->question_text) }}</textarea>
                    <p id="question-text-hint" class="mt-1 text-sm text-gray-500 hidden"></p>
                    @error('question_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Options Section -->
                <div id="optionsSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700"><span id="options-label">Options</span></label>
                    <p id="options-hint" class="text-sm text-gray-500 mb-2"></p>
                    <div id="optionsContainer" class="mt-2 space-y-2">
                        @foreach(old('options', $question->options ?? []) as $option)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="options[]" value="{{ $option }}" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Option">
                            <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addOption()" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Add Option</button>
                </div>

                <!-- Ordering Items Section -->
                <div id="orderingSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Items to Order (in correct order) *</label>
                    <p class="text-sm text-gray-500 mb-2">Enter items in the correct order. Students will see them shuffled.</p>
                    <div id="orderingContainer" class="mt-2 space-y-2">
                        @if(old('type', $question->type) === 'ordering')
                            @foreach(old('correct_answers', $question->correct_answers ?? []) as $index => $item)
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-500 text-sm w-6">{{ $index + 1 }}.</span>
                                <input type="text" name="correct_answers[]" value="{{ $item }}" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Item {{ $index + 1 }}">
                                <button type="button" onclick="removeOrderingItem(this)" class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addOrderingItem()" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Add Item</button>
                </div>

                <!-- Matching hint -->
                <div id="matchingSection" class="hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <p class="text-sm text-blue-700">Matching: Left items go in Correct Answers, right items in Options. Answer 1 matches Option 1, etc.</p>
                    </div>
                </div>

                <!-- Diagram Section -->
                <div id="diagramSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Diagram Image URL</label>
                    <input type="text" name="metadata[image_url]"
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="https://example.com/diagram.png"
                           value="{{ old('metadata.image_url', data_get($question->metadata, 'image_url')) }}">
                    <p class="mt-1 text-sm text-gray-500">URL to the diagram image. Labels use Options as word bank and Correct Answers for positions.</p>
                </div>

                <!-- Correct Answers -->
                <div id="correctAnswersSection">
                    <label class="block text-sm font-medium text-gray-700"><span id="answers-label">Correct Answer(s)</span> *</label>
                    <p id="answers-hint" class="text-sm text-gray-500 mb-2 hidden"></p>
                    <div id="correctAnswersContainer" class="mt-2 space-y-2">
                        @foreach(old('correct_answers', $question->correct_answers ?? ['']) as $answer)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="correct_answers[]" value="{{ $answer }}" required class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Correct answer">
                            <button type="button" onclick="removeCorrectAnswer(this)" class="text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addCorrectAnswer()" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Add Answer</button>
                </div>

                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700">Points *</label>
                    <input type="number" name="points" id="points" required min="1" max="10" value="{{ old('points', $question->points) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('points') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="order" id="order" min="1" value="{{ old('order', $question->order) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.show', $question->test) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update Question</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const typeConfig = {
    multiple_choice: { showOptions: true, showCorrectAnswers: true, optionsLabel: 'Options', optionsHint: 'Add at least 2 options.', answersLabel: 'Correct Answer', answersHint: 'Enter the exact text of the correct option.', questionHint: '', showOrdering: false, showMatching: false, showDiagram: false },
    gap_filling: { showOptions: false, showCorrectAnswers: true, answersLabel: 'Correct Answer(s)', answersHint: 'Enter each blank answer in order.', questionHint: 'Use _____ to indicate blanks.', showOrdering: false, showMatching: false, showDiagram: false },
    select_options: { showOptions: true, showCorrectAnswers: true, optionsLabel: 'Options', optionsHint: 'Add all options. Students can select multiple.', answersLabel: 'Correct Answers', answersHint: 'Enter all correct answers.', questionHint: '', showOrdering: false, showMatching: false, showDiagram: false },
    true_false_notgiven: { showOptions: false, showCorrectAnswers: true, answersLabel: 'Correct Answer', answersHint: 'Enter exactly: True, False, or Not Given', questionHint: 'Enter the statement to evaluate.', showOrdering: false, showMatching: false, showDiagram: false },
    yes_no_notgiven: { showOptions: false, showCorrectAnswers: true, answersLabel: 'Correct Answer', answersHint: 'Enter exactly: Yes, No, or Not Given', questionHint: 'Enter the statement to evaluate.', showOrdering: false, showMatching: false, showDiagram: false },
    matching: { showOptions: true, showCorrectAnswers: true, optionsLabel: 'Right Column Items', optionsHint: 'Items students choose from.', answersLabel: 'Left Column Items = Correct Matches', answersHint: 'Answer 1 matches Option 1, etc.', questionHint: 'Describe the matching task.', showOrdering: false, showMatching: true, showDiagram: false },
    sentence_completion: { showOptions: true, showCorrectAnswers: true, optionsLabel: 'Word Bank', optionsHint: 'Words/phrases for blanks.', answersLabel: 'Correct Answers (in blank order)', answersHint: 'Correct word for each blank, in order.', questionHint: 'Use _____ for blanks.', showOrdering: false, showMatching: false, showDiagram: false },
    short_answer: { showOptions: false, showCorrectAnswers: true, answersLabel: 'Accepted Answers', answersHint: 'Enter all accepted variations.', questionHint: 'Students type a short answer (max 3 words).', showOrdering: false, showMatching: false, showDiagram: false },
    diagram_labeling: { showOptions: true, showCorrectAnswers: true, optionsLabel: 'Word Bank', optionsHint: 'Words to label the diagram.', answersLabel: 'Correct Labels (position order)', answersHint: 'Correct label for each position.', questionHint: 'Describe the diagram.', showOrdering: false, showMatching: false, showDiagram: true },
    ordering: { showOptions: false, showCorrectAnswers: false, questionHint: 'Describe what to order.', showOrdering: true, showMatching: false, showDiagram: false },
};

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('type').addEventListener('change', updateFormForType);
    updateFormForType();
});

function updateFormForType() {
    const type = document.getElementById('type').value;
    const config = typeConfig[type];
    if (!config) return;

    document.getElementById('optionsSection').classList.toggle('hidden', !config.showOptions);
    if (config.optionsLabel) document.getElementById('options-label').textContent = config.optionsLabel;
    if (config.optionsHint) { document.getElementById('options-hint').textContent = config.optionsHint; document.getElementById('options-hint').classList.remove('hidden'); }

    document.getElementById('correctAnswersSection').classList.toggle('hidden', !config.showCorrectAnswers);
    if (config.answersLabel) document.getElementById('answers-label').textContent = config.answersLabel;
    const ah = document.getElementById('answers-hint');
    if (config.answersHint) { ah.textContent = config.answersHint; ah.classList.remove('hidden'); } else { ah.classList.add('hidden'); }

    const qh = document.getElementById('question-text-hint');
    if (config.questionHint) { qh.textContent = config.questionHint; qh.classList.remove('hidden'); } else { qh.classList.add('hidden'); }

    document.getElementById('orderingSection').classList.toggle('hidden', !config.showOrdering);
    document.getElementById('matchingSection').classList.toggle('hidden', !config.showMatching);
    document.getElementById('diagramSection').classList.toggle('hidden', !config.showDiagram);
}

function addOption() {
    const c = document.getElementById('optionsContainer');
    const d = document.createElement('div');
    d.className = 'flex items-center space-x-2';
    d.innerHTML = '<input type="text" name="options[]" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Option"><button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-900"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>';
    c.appendChild(d);
}

function removeOption(btn) { btn.parentElement.remove(); }

function addCorrectAnswer() {
    const c = document.getElementById('correctAnswersContainer');
    const d = document.createElement('div');
    d.className = 'flex items-center space-x-2';
    d.innerHTML = '<input type="text" name="correct_answers[]" required class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Correct answer"><button type="button" onclick="removeCorrectAnswer(this)" class="text-red-600 hover:text-red-900"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>';
    c.appendChild(d);
}

function removeCorrectAnswer(btn) {
    if (document.getElementById('correctAnswersContainer').children.length > 1) btn.parentElement.remove();
}

function addOrderingItem() {
    const c = document.getElementById('orderingContainer');
    const count = c.children.length + 1;
    const d = document.createElement('div');
    d.className = 'flex items-center space-x-2';
    d.innerHTML = `<span class="text-gray-500 text-sm w-6">${count}.</span><input type="text" name="correct_answers[]" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Item ${count}"><button type="button" onclick="removeOrderingItem(this)" class="text-red-600 hover:text-red-900"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>`;
    c.appendChild(d);
    renumberOrderingItems();
}

function removeOrderingItem(btn) {
    if (document.getElementById('orderingContainer').children.length > 1) { btn.parentElement.remove(); renumberOrderingItems(); }
}

function renumberOrderingItems() {
    document.querySelectorAll('#orderingContainer > div').forEach((d, i) => { d.querySelector('span').textContent = (i+1)+'.'; });
}
</script>
@endpush
@endsection