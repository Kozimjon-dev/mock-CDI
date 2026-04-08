<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Student;
use App\Models\TestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function register(Test $test)
    {
        if (!$test->is_published || $test->status !== 'active') {
            abort(404, 'Test not available');
        }

        return view('student.register', compact('test'));
    }

    public function storeRegistration(Request $request, Test $test)
    {
        if (!$test->is_published || $test->status !== 'active') {
            abort(404, 'Test not available');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255'
        ]);

        // Create student
        $student = Student::create([
            ...$validated,
            'session_id' => Str::uuid()
        ]);

        // Create test session
        $session = TestSession::create([
            'student_id' => $student->id,
            'test_id' => $test->id,
            'session_token' => Str::random(32),
            'started_at' => now(),
            'current_module' => 'dashboard'
        ]);

        return redirect()->route('student.session.show', $session->session_token)
            ->with('success', 'Registration successful! You can now start your test.');
    }
}
