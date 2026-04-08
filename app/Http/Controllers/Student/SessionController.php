<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\StudentResponse;
use App\Models\WritingResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    public function show(string $sessionToken)
    {
        $session = TestSession::where('session_token', $sessionToken)
            ->with(['student', 'test'])
            ->firstOrFail();

        if ($session->isCompleted()) {
            $test = $session->test;

            $scores = [
                'listening' => [
                    'correct' => StudentResponse::where('student_id', $session->student_id)
                        ->where('test_id', $session->test_id)
                        ->where('module', 'listening')
                        ->where('is_correct', true)->count(),
                    'total' => $test->listeningQuestions()->count(),
                ],
                'reading' => [
                    'correct' => StudentResponse::where('student_id', $session->student_id)
                        ->where('test_id', $session->test_id)
                        ->where('module', 'reading')
                        ->where('is_correct', true)->count(),
                    'total' => $test->readingQuestions()->count(),
                ],
            ];

            $scores['overall'] = [
                'correct' => $scores['listening']['correct'] + $scores['reading']['correct'],
                'total' => $scores['listening']['total'] + $scores['reading']['total'],
            ];

            return view('student.completed', compact('session', 'scores'));
        }

        return view('student.dashboard', compact('session'));
    }

    public function listening(string $sessionToken)
    {
        $session = $this->getSession($sessionToken);

        if ($session->isModuleCompleted('listening')) {
            return redirect()->route('student.session.show', $sessionToken);
        }

        $session->update(['current_module' => 'listening']);
        $session->markModuleStarted('listening');

        $test = $session->test;
        $audioMaterial = $test->listeningMaterials()->first();
        $questions = $test->listeningQuestions()->get()->groupBy('part');

        return view('student.listening', compact('session', 'test', 'audioMaterial', 'questions'));
    }

    public function reading(string $sessionToken)
    {
        $session = $this->getSession($sessionToken);

        if ($session->isModuleCompleted('reading')) {
            return redirect()->route('student.session.show', $sessionToken);
        }

        $session->update(['current_module' => 'reading']);
        $session->markModuleStarted('reading');

        $test = $session->test;
        $materials = $test->readingMaterials()->orderBy('part')->get();
        $questions = $test->readingQuestions()->get()->groupBy('part');

        return view('student.reading', compact('session', 'test', 'materials', 'questions'));
    }

    public function writing(string $sessionToken)
    {
        $session = $this->getSession($sessionToken);

        if ($session->isModuleCompleted('writing')) {
            return redirect()->route('student.session.show', $sessionToken);
        }

        $session->update(['current_module' => 'writing']);
        $session->markModuleStarted('writing');

        $test = $session->test;
        $writingQuestions = $test->writingQuestions()->get();
        $existingResponses = $session->writingResponses()->get()->keyBy('task');

        return view('student.writing', compact('session', 'test', 'writingQuestions', 'existingResponses'));
    }

    public function submitAnswer(Request $request, string $sessionToken): JsonResponse
    {
        $session = $this->getSession($sessionToken);

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required'
        ]);

        $question = $session->test->questions()->findOrFail($validated['question_id']);

        // Check if answer is correct
        $isCorrect = $question->checkAnswer($validated['answer']);
        $points = $isCorrect ? $question->points : 0;

        // Save or update response
        StudentResponse::updateOrCreate(
            [
                'student_id' => $session->student_id,
                'test_id' => $session->test_id,
                'question_id' => $question->id
            ],
            [
                'student_answer' => $validated['answer'],
                'is_correct' => $isCorrect,
                'points_earned' => $points,
                'module' => $question->module,
                'answered_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'points' => $points
        ]);
    }

    public function submitWriting(Request $request, string $sessionToken): JsonResponse
    {
        $session = $this->getSession($sessionToken);

        $validated = $request->validate([
            'task' => 'required|in:task_1,task_2',
            'content' => 'required|string|min:10'
        ]);

        $response = WritingResponse::updateOrCreate(
            [
                'student_id' => $session->student_id,
                'test_id' => $session->test_id,
                'test_session_id' => $session->id,
                'task' => $validated['task']
            ],
            [
                'response_content' => $validated['content'],
                'word_count' => 0, // Will be calculated
                'started_at' => now()
            ]
        );

        $response->updateWordCount();

        return response()->json([
            'success' => true,
            'word_count' => $response->word_count
        ]);
    }

    public function completeModule(Request $request, string $sessionToken): JsonResponse
    {
        $session = $this->getSession($sessionToken);

        $validated = $request->validate([
            'module' => 'required|in:listening,reading,writing'
        ]);

        $module = $validated['module'];

        // Mark module as completed
        $session->markModuleCompleted($module);

        // Check if all modules are completed
        $allCompleted = $session->isModuleCompleted('listening')
            && $session->isModuleCompleted('reading')
            && $session->isModuleCompleted('writing');

        if ($allCompleted) {
            $session->update([
                'current_module' => 'completed',
                'completed_at' => now(),
            ]);
        } else {
            // Set current_module to dashboard (not a specific module)
            $session->update(['current_module' => 'dashboard']);
        }

        return response()->json([
            'success' => true,
            'next_module' => $allCompleted ? 'completed' : 'dashboard',
            'is_completed' => $allCompleted
        ]);
    }

    public function completeTest(Request $request, string $sessionToken): JsonResponse
    {
        $session = $this->getSession($sessionToken);

        $session->update([
            'completed_at' => now(),
            'current_module' => 'completed'
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('student.session.show', $sessionToken)
        ]);
    }

    public function heartbeat(Request $request, string $sessionToken): JsonResponse
    {
        $session = $this->getSession($sessionToken);

        // Check for potential cheating attempts
        $this->checkForCheating($request, $session);

        return response()->json(['success' => true]);
    }

    private function getSession(string $sessionToken): TestSession
    {
        $session = TestSession::where('session_token', $sessionToken)
            ->with(['student', 'test'])
            ->firstOrFail();

        if ($session->isCompleted()) {
            redirect()->route('student.session.show', $sessionToken);
        }

        return $session;
    }

    private function getNextModule(string $currentModule): string
    {
        return match($currentModule) {
            'listening' => 'reading',
            'reading' => 'writing',
            'writing' => 'completed',
            default => 'completed'
        };
    }

    private function checkForCheating(Request $request, TestSession $session): void
    {
        $cheatAttempts = [];

        // Check if page is in fullscreen
        if (!$request->input('fullscreen', false)) {
            $cheatAttempts[] = 'Page not in fullscreen mode';
        }

        // Check if window is focused
        if (!$request->input('focused', true)) {
            $cheatAttempts[] = 'Window lost focus';
        }

        // Check for right-click attempts
        if ($request->input('right_click', false)) {
            $cheatAttempts[] = 'Right-click detected';
        }

        // Check for keyboard shortcuts
        if ($request->input('keyboard_shortcut', false)) {
            $cheatAttempts[] = 'Keyboard shortcut detected';
        }

        // Check for tab switching
        if ($request->input('tab_switch', false)) {
            $cheatAttempts[] = 'Tab switching detected';
        }

        if (!empty($cheatAttempts)) {
            foreach ($cheatAttempts as $attempt) {
                $session->recordCheatAttempt($attempt);
            }
        }
    }
}
