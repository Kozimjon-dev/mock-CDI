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
        $validated = $request->validate([
            'test_id' => 'required|exists:tests,id',
            'module' => ['required', Rule::in(['listening', 'reading', 'writing'])],
            'part' => 'required|integer|min:1|max:4',
            'type' => ['required', Rule::in(['multiple_choice', 'gap_filling', 'select_options'])],
            'question_text' => 'required|string|max:1000',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'string|max:255',
            'points' => 'required|integer|min:1|max:10',
            'order' => 'nullable|integer|min:1',
        ]);

        // Validate options for multiple choice and select options
        if (in_array($validated['type'], ['multiple_choice', 'select_options'])) {
            if (empty($validated['options']) || count($validated['options']) < 2) {
                return back()->withErrors(['options' => 'Multiple choice and select options questions must have at least 2 options.'])->withInput();
            }
        }

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
        $validated = $request->validate([
            'module' => ['required', Rule::in(['listening', 'reading', 'writing'])],
            'part' => 'required|integer|min:1|max:4',
            'type' => ['required', Rule::in(['multiple_choice', 'gap_filling', 'select_options'])],
            'question_text' => 'required|string|max:1000',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'string|max:255',
            'points' => 'required|integer|min:1|max:10',
            'order' => 'nullable|integer|min:1',
        ]);

        // Validate options for multiple choice and select options
        if (in_array($validated['type'], ['multiple_choice', 'select_options'])) {
            if (empty($validated['options']) || count($validated['options']) < 2) {
                return back()->withErrors(['options' => 'Multiple choice and select options questions must have at least 2 options.'])->withInput();
            }
        }

        $question->update([
            'module' => $validated['module'],
            'part' => $validated['part'],
            'type' => $validated['type'],
            'question_text' => $validated['question_text'],
            'options' => $validated['options'] ?? [],
            'correct_answers' => $validated['correct_answers'],
            'points' => $validated['points'],
            'order' => $validated['order'] ?? 1,
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
}
