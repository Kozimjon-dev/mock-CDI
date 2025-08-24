<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestSession;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::latest()->paginate(15);
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.tests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'listening_time' => 'required|integer|min:1|max:120',
            'reading_time' => 'required|integer|min:1|max:180',
            'writing_time' => 'required|integer|min:1|max:180',
            'status' => 'required|in:draft,active,inactive'
        ]);

        $test = Test::create($validated);

        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Test created successfully!');
    }

    public function show(Test $test)
    {
        $test->load(['materials', 'questions']);
        return view('admin.tests.show', compact('test'));
    }

    public function edit(Test $test)
    {
        return view('admin.tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'listening_time' => 'required|integer|min:1|max:180',
            'reading_time' => 'required|integer|min:1|max:180',
            'writing_time' => 'required|integer|min:1|max:180',
            'status' => 'required|in:draft,active,inactive'
        ]);

        $test->update($validated);

        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Test updated successfully!');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')
            ->with('success', 'Test deleted successfully!');
    }

    public function publish(Test $test)
    {
        $test->update(['is_published' => true, 'status' => 'active']);
        return redirect()->back()->with('success', 'Test published successfully!');
    }

    public function unpublish(Test $test)
    {
        $test->update(['is_published' => false]);
        return redirect()->back()->with('success', 'Test unpublished successfully!');
    }

    public function results(Test $test)
    {
        $sessions = $test->testSessions()
            ->with(['student', 'studentResponses'])
            ->whereNotNull('completed_at')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_sessions' => $test->testSessions()->whereNotNull('completed_at')->count(),
            'total_students' => $test->testSessions()->whereNotNull('completed_at')->distinct('student_id')->count(),
            'avg_listening_score' => $this->calculateAverageScore($test, 'listening'),
            'avg_reading_score' => $this->calculateAverageScore($test, 'reading'),
        ];

        return view('admin.tests.results', compact('test', 'sessions', 'stats'));
    }

    public function sessions(Test $test)
    {
        $sessions = $test->testSessions()
            ->with('student')
            ->latest()
            ->paginate(20);

        return view('admin.tests.sessions', compact('test', 'sessions'));
    }

    private function calculateAverageScore(Test $test, string $module): float
    {
        $responses = $test->testSessions()
            ->join('student_responses', 'test_sessions.student_id', '=', 'student_responses.student_id')
            ->where('student_responses.module', $module)
            ->where('student_responses.is_correct', true)
            ->count();

        $totalQuestions = $test->questions()->where('module', $module)->count();
        $totalSessions = $test->testSessions()->whereNotNull('completed_at')->count();

        if ($totalSessions === 0 || $totalQuestions === 0) {
            return 0;
        }

        return round(($responses / ($totalQuestions * $totalSessions)) * 100, 2);
    }
}
