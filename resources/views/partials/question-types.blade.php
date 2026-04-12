{{-- Shared question type rendering partial --}}
{{-- Variables: $question, $theme (dark|light) --}}
@php
    $isDark = ($theme ?? 'light') === 'dark';
    $textClass = $isDark ? 'text-gray-300' : 'text-gray-700';
    $inputBg = $isDark ? 'bg-gray-700 border-gray-600 text-white' : 'border-gray-300';
    $labelClass = $isDark ? 'text-gray-300 cursor-pointer' : 'text-gray-700 cursor-pointer';
    $selectClass = $isDark ? 'bg-gray-700 border-gray-600 text-white' : 'border-gray-300 text-gray-900';
    $hintClass = $isDark ? 'text-gray-400' : 'text-gray-500';
    $dragBg = $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-200';
    $dragHoverBg = $isDark ? 'hover:bg-gray-600' : 'hover:bg-gray-100';
@endphp

@if($question->isMultipleChoice())
    <div class="space-y-3">
        @foreach($question->options_array as $index => $option)
        <label class="flex items-center space-x-3 {{ $labelClass }}">
            <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}"
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
            <span class="{{ $textClass }}">{{ chr(65 + $index) }}. {{ $option }}</span>
        </label>
        @endforeach
    </div>

@elseif($question->isGapFilling())
    <div class="space-y-3">
        @foreach($question->correct_answers_array as $index => $answer)
        <div class="flex items-center space-x-3">
            <span class="{{ $textClass }}">{{ $index + 1 }}.</span>
            <input type="text" name="question_{{ $question->id }}[]"
                   class="flex-1 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 {{ $inputBg }}"
                   placeholder="Enter your answer">
        </div>
        @endforeach
    </div>

@elseif($question->isSelectOptions())
    <div class="space-y-3">
        @foreach($question->options_array as $index => $option)
        <label class="flex items-center space-x-3 {{ $labelClass }}">
            <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $option }}"
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <span class="{{ $textClass }}">{{ $option }}</span>
        </label>
        @endforeach
    </div>

@elseif($question->isTrueFalseNotGiven())
    <div class="space-y-3">
        @foreach(['True', 'False', 'Not Given'] as $option)
        <label class="flex items-center space-x-3 {{ $labelClass }}">
            <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}"
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
            <span class="{{ $textClass }}">{{ $option }}</span>
        </label>
        @endforeach
    </div>

@elseif($question->isYesNoNotGiven())
    <div class="space-y-3">
        @foreach(['Yes', 'No', 'Not Given'] as $option)
        <label class="flex items-center space-x-3 {{ $labelClass }}">
            <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}"
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
            <span class="{{ $textClass }}">{{ $option }}</span>
        </label>
        @endforeach
    </div>

@elseif($question->isMatching())
    {{-- Matching: left items (correct_answers keys) with dropdown of right items (options) --}}
    <div class="space-y-3">
        @foreach($question->correct_answers_array as $index => $leftItem)
        <div class="flex items-center space-x-3">
            <span class="{{ $textClass }} font-medium min-w-0 flex-shrink-0">{{ $index + 1 }}. {{ $leftItem }}</span>
            <svg class="h-4 w-4 {{ $hintClass }} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
            <select name="question_{{ $question->id }}[{{ $index }}]"
                    class="flex-1 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 {{ $selectClass }}">
                <option value="">-- Select --</option>
                @foreach($question->options_array as $optIndex => $rightItem)
                <option value="{{ $rightItem }}">{{ chr(65 + $optIndex) }}. {{ $rightItem }}</option>
                @endforeach
            </select>
        </div>
        @endforeach
    </div>

@elseif($question->isSentenceCompletion())
    {{-- Sentence completion: display text with dropdown blanks --}}
    @php
        $parts = preg_split('/_{3,}/', $question->question_text);
        $blankCount = count($parts) - 1;
    @endphp
    <div class="{{ $textClass }} leading-relaxed">
        @foreach($parts as $pIndex => $textPart)
            <span>{!! nl2br(e($textPart)) !!}</span>
            @if($pIndex < $blankCount)
                <select name="question_{{ $question->id }}[{{ $pIndex }}]"
                        class="inline-block mx-1 rounded-md px-2 py-1 text-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $selectClass }}">
                    <option value="">______</option>
                    @foreach($question->options_array as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
            @endif
        @endforeach
    </div>

@elseif($question->isShortAnswer())
    <div>
        <input type="text" name="question_{{ $question->id }}" maxlength="50"
               class="w-full rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 {{ $inputBg }}"
               placeholder="Type your answer (max 3 words)">
        <p class="mt-1 text-sm {{ $hintClass }}">Write no more than 3 words.</p>
    </div>

@elseif($question->isDiagramLabeling())
    {{-- Diagram with image + labeled positions using word bank --}}
    @if($question->meta('image_url'))
    <div class="mb-4">
        <img src="{{ $question->meta('image_url') }}" alt="Diagram" class="max-w-full rounded-lg border {{ $isDark ? 'border-gray-600' : 'border-gray-300' }}">
    </div>
    @endif
    <div class="space-y-3">
        @foreach($question->correct_answers_array as $index => $label)
        <div class="flex items-center space-x-3">
            <span class="{{ $textClass }} font-medium">Label {{ $index + 1 }}:</span>
            <select name="question_{{ $question->id }}[{{ $index }}]"
                    class="flex-1 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 {{ $selectClass }}">
                <option value="">-- Select --</option>
                @foreach($question->options_array as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        @endforeach
    </div>

@elseif($question->isOrdering())
    {{-- Draggable ordering list --}}
    @php
        $items = $question->correct_answers_array;
        $shuffled = $items;
        shuffle($shuffled);
    @endphp
    <div class="ordering-container" data-question-id="{{ $question->id }}">
        <p class="text-sm {{ $hintClass }} mb-3">Drag items to put them in the correct order.</p>
        <div class="ordering-list space-y-2" data-question-id="{{ $question->id }}">
            @foreach($shuffled as $index => $item)
            <div class="ordering-item flex items-center space-x-3 p-3 rounded-lg border cursor-move {{ $dragBg }} {{ $dragHoverBg }}" draggable="true" data-value="{{ $item }}">
                <span class="ordering-number {{ $hintClass }} font-medium w-6">{{ $index + 1 }}.</span>
                <svg class="h-5 w-5 {{ $hintClass }} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
                <span class="{{ $textClass }}">{{ $item }}</span>
                <input type="hidden" name="question_{{ $question->id }}[]" value="{{ $item }}">
            </div>
            @endforeach
        </div>
    </div>
@endif