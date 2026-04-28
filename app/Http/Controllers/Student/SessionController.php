<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\Student;
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
            $scores = $session->getModuleScores();
            $badges = $this->calculateBadges($session, $scores);

            return view('student.completed', compact('session', 'scores', 'badges'));
        }

        return view('student.dashboard', compact('session'));
    }

    public function history(Request $request)
    {
        $phone = $request->query('phone');
        $sessions = collect();
        $student = null;

        if ($phone) {
            $student = Student::where('phone_number', $phone)->first();

            if ($student) {
                $sessions = TestSession::where('student_id', $student->id)
                    ->whereNotNull('completed_at')
                    ->with('test')
                    ->orderBy('completed_at', 'desc')
                    ->get();

                // Calculate band scores for each session
                $sessions->each(function ($session) {
                    $session->module_scores = $session->getModuleScores();
                });
            }
        }

        return view('student.history', compact('phone', 'student', 'sessions'));
    }

    public function review(string $sessionToken)
    {
        $session = TestSession::where('session_token', $sessionToken)
            ->with(['student', 'test'])
            ->firstOrFail();

        if (!$session->isCompleted()) {
            return redirect()->route('student.session.show', $sessionToken);
        }

        $test = $session->test;
        $scores = $session->getModuleScores();

        // Get all student responses keyed by question_id
        $responses = StudentResponse::where('student_id', $session->student_id)
            ->where('test_id', $session->test_id)
            ->get()
            ->keyBy('question_id');

        // Get questions grouped by module
        $listeningQuestions = $test->listeningQuestions()->get();
        $readingQuestions = $test->readingQuestions()->get();

        // Get writing responses
        $writingResponses = $session->writingResponses()->get()->keyBy('task');

        return view('student.review', compact(
            'session', 'scores', 'responses',
            'listeningQuestions', 'readingQuestions', 'writingResponses'
        ));
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
        $audioMaterials = $test->listeningMaterials()->orderBy('part')->get()->keyBy('part');
        $questions = $test->listeningQuestions()->get()->groupBy('part');

        return view('student.listening', compact('session', 'test', 'audioMaterials', 'questions'));
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
        $answer = $validated['answer'];

        // Check if answer is correct
        $isCorrect = $question->checkAnswer($answer);
        $points = $isCorrect ? $question->points : 0;

        // Store answer as JSON string if array
        $storedAnswer = is_array($answer) ? json_encode($answer) : $answer;

        // Save or update response
        StudentResponse::updateOrCreate(
            [
                'student_id' => $session->student_id,
                'test_id' => $session->test_id,
                'question_id' => $question->id
            ],
            [
                'student_answer' => $storedAnswer,
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

    private function calculateBadges(TestSession $session, array $scores): array
    {
        $badges = [];
        $studentId = $session->student_id;

        // Get all completed sessions for this student
        $allSessions = TestSession::where('student_id', $studentId)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'asc')
            ->get();

        $sessionCount = $allSessions->count();

        // First Test
        if ($sessionCount >= 1) {
            $badges[] = ['icon' => '🎯', 'name' => 'First Test', 'color' => 'blue'];
        }

        // Consistent (3+ tests)
        if ($sessionCount >= 3) {
            $badges[] = ['icon' => '🔁', 'name' => 'Consistent', 'color' => 'purple'];
        }

        // Band 6+ / Band 7+
        $overallBand = $scores['overall']['band'];
        if ($overallBand >= 6.0) {
            $badges[] = ['icon' => '⭐', 'name' => 'Band 6+', 'color' => 'yellow'];
        }
        if ($overallBand >= 7.0) {
            $badges[] = ['icon' => '🏆', 'name' => 'Band 7+', 'color' => 'amber'];
        }

        // Perfect Listener / Reader
        if ($scores['listening']['total'] > 0 && $scores['listening']['correct'] === $scores['listening']['total']) {
            $badges[] = ['icon' => '🎧', 'name' => 'Perfect Listener', 'color' => 'green'];
        }
        if ($scores['reading']['total'] > 0 && $scores['reading']['correct'] === $scores['reading']['total']) {
            $badges[] = ['icon' => '📖', 'name' => 'Perfect Reader', 'color' => 'green'];
        }

        // Improving (latest band > first band)
        if ($sessionCount >= 2) {
            $firstSession = $allSessions->first();
            $latestSession = $allSessions->last();
            $firstBand = $firstSession->getOverallBandScore();
            $latestBand = $latestSession->getOverallBandScore();
            if ($latestBand > $firstBand) {
                $badges[] = ['icon' => '📈', 'name' => 'Improving', 'color' => 'indigo'];
            }
        }

        return $badges;
    }

    private function getSession(string $sessionToken): TestSession
    {
        return TestSession::where('session_token', $sessionToken)
            ->with(['student', 'test'])
            ->firstOrFail();
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
