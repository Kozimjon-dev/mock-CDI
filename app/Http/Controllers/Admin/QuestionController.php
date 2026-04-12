<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::with(['test', 'material'])->latest()->paginate(20);
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $test = Test::findOrFail($request->test);
        return view('admin.questions.create', compact('test'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        $this->validateTypeSpecificRules($validated);

        $question = Question::create([
            'test_id' => $validated['test_id'],
            'module' => $validated['module'],
            'part' => $validated['part'],
            'type' => $validated['type'],
            'question_text' => $validated['question_text'],
            'options' => $validated['options'] ?? [],
            'correct_answers' => $validated['correct_answers'],
            'points' => $validated['points'],
            'order' => $validated['order'] ?? 1,
            'metadata' => $validated['metadata'] ?? null,
        ]);

        return redirect()->route('admin.tests.show', $validated['test_id'])
            ->with('success', 'Question added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        $question->load(['test', 'material']);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $question->load('test');
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $rules = $this->validationRules();
        unset($rules['test_id']);
        $validated = $request->validate($rules);

        $this->validateTypeSpecificRules($validated);

        $question->update([
            'module' => $validated['module'],
            'part' => $validated['part'],
            'type' => $validated['type'],
            'question_text' => $validated['question_text'],
            'options' => $validated['options'] ?? [],
            'correct_answers' => $validated['correct_answers'],
            'points' => $validated['points'],
            'order' => $validated['order'] ?? 1,
            'metadata' => $validated['metadata'] ?? null,
        ]);

        return redirect()->route('admin.tests.show', $question->test_id)
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $testId = $question->test_id;
        $question->delete();

        return redirect()->route('admin.tests.show', $testId)
            ->with('success', 'Question deleted successfully!');
    }

    private function validationRules(): array
    {
        return [
            'test_id' => 'required|exists:tests,id',
            'module' => ['required', Rule::in(['listening', 'reading', 'writing'])],
            'part' => 'required|integer|min:1|max:4',
            'type' => ['required', Rule::in(Question::TYPES)],
            'question_text' => 'required|string|max:5000',
            'options' => 'nullable|array',
            'options.*' => 'string|max:500',
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'nullable|string|max:500',
            'points' => 'required|integer|min:1|max:10',
            'order' => 'nullable|integer|min:1',
            'metadata' => 'nullable|array',
        ];
    }

    private function validateTypeSpecificRules(array $validated): void
    {
        $type = $validated['type'];

        // Types that require options with at least 2 items
        $typesNeedingOptions = ['multiple_choice', 'select_options', 'sentence_completion'];
        if (in_array($type, $typesNeedingOptions)) {
            if (empty($validated['options']) || count($validated['options']) < 2) {
                abort(back()->withErrors(['options' => "{$type} questions must have at least 2 options."])->withInput());
            }
        }

        // Matching needs options (right-side items) for the dropdown
        if ($type === 'matching') {
            if (empty($validated['options']) || count($validated['options']) < 2) {
                abort(back()->withErrors(['options' => 'Matching questions must have at least 2 right-side items.'])->withInput());
            }
        }
    }
}
