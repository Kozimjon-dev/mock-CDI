@extends('layouts.admin')

@section('title', 'Edit Question')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Edit Question</h2>
        <p class="mt-1 text-gray-600">From: {{ $question->test->title }}</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.questions.update', $question) }}" method="POST" id="questionForm">
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
                        <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                        <option value="gap_filling" {{ old('type', $question->type) == 'gap_filling' ? 'selected' : '' }}>Gap Filling</option>
                        <option value="select_options" {{ old('type', $question->type) == 'select_options' ? 'selected' : '' }}>Select Options</option>
                    </select>
                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700">Question Text *</label>
                    <textarea name="question_text" id="question_text" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="optionsSection" class="{{ in_array(old('type', $question->type), ['multiple_choice', 'select_options']) ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700">Options</label>
                    <div id="optionsContainer" class="mt-2 space-y-2">
                        @foreach(old('options', $question->options ?? []) as $option)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="options[]" value="{{ $option }}" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Option">
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addOption()" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Add Option</button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Correct Answer(s) *</label>
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
document.getElementById('type').addEventListener('change', function() {
    document.getElementById('optionsSection').classList.toggle('hidden', !['multiple_choice', 'select_options'].includes(this.value));
});

function addOption() {
    const c = document.getElementById('optionsContainer');
    const d = document.createElement('div');
    d.className = 'flex items-center space-x-2';
    d.innerHTML = '<input type="text" name="options[]" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Option"><button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>';
    c.appendChild(d);
}

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
</script>
@endpush
@endsection
