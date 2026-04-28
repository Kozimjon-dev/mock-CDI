<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSession;
use App\Models\Student;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $publishedTests = Test::published()->latest()->get();
        $totalTests = $publishedTests->count();
        $totalStudents = Student::count();
        $completedSessions = TestSession::whereNotNull('completed_at')->count();

        return view('home', compact('publishedTests', 'totalTests', 'totalStudents', 'completedSessions'));
    }

    public function tests()
    {
        $tests = Test::published()->latest()->paginate(12);
        return view('tests.index', compact('tests'));
    }

    public function showTest(Test $test)
    {
        if (!$test->is_published || $test->status !== 'active') {
            abort(404);
        }

        return view('tests.show', compact('test'));
    }
}
