<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Student\TestController as StudentTestController;
use App\Http\Controllers\Student\SessionController as StudentSessionController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tests', [HomeController::class, 'tests'])->name('tests');
Route::get('/test/{test}', [HomeController::class, 'showTest'])->name('test.show');

// Student registration and test taking
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/register/{test}', [StudentTestController::class, 'register'])->name('register');
    Route::post('/register/{test}', [StudentTestController::class, 'storeRegistration'])->name('store-registration');

    // Test session routes (protected by session token)
    Route::prefix('session')->name('session.')->group(function () {
        Route::get('/{sessionToken}', [StudentSessionController::class, 'show'])->name('show');
        Route::get('/{sessionToken}/listening', [StudentSessionController::class, 'listening'])->name('listening');
        Route::get('/{sessionToken}/reading', [StudentSessionController::class, 'reading'])->name('reading');
        Route::get('/{sessionToken}/writing', [StudentSessionController::class, 'writing'])->name('writing');
        Route::post('/{sessionToken}/answer', [StudentSessionController::class, 'submitAnswer'])->name('submit-answer');
        Route::post('/{sessionToken}/writing', [StudentSessionController::class, 'submitWriting'])->name('submit-writing');
        Route::post('/{sessionToken}/complete-module', [StudentSessionController::class, 'completeModule'])->name('complete-module');
        Route::post('/{sessionToken}/complete-test', [StudentSessionController::class, 'completeTest'])->name('complete-test');
        Route::post('/{sessionToken}/heartbeat', [StudentSessionController::class, 'heartbeat'])->name('heartbeat');
    });
});

// Admin routes (you might want to add authentication middleware later)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminTestController::class, 'index'])->name('dashboard');

    // Test management
    Route::resource('tests', AdminTestController::class);
    Route::post('tests/{test}/publish', [AdminTestController::class, 'publish'])->name('tests.publish');
    Route::post('tests/{test}/unpublish', [AdminTestController::class, 'unpublish'])->name('tests.unpublish');

    // Material management
    Route::get('materials/create', [AdminMaterialController::class, 'create'])->name('materials.create');
    Route::post('materials', [AdminMaterialController::class, 'store'])->name('materials.store');
    Route::resource('materials', AdminMaterialController::class)->except(['create', 'store']);

    // Question management
    Route::get('questions/create', [AdminQuestionController::class, 'create'])->name('questions.create');
    Route::post('questions', [AdminQuestionController::class, 'store'])->name('questions.store');
    Route::resource('questions', AdminQuestionController::class)->except(['create', 'store']);

    // Results and analytics
    Route::get('tests/{test}/results', [AdminTestController::class, 'results'])->name('tests.results');
    Route::get('tests/{test}/sessions', [AdminTestController::class, 'sessions'])->name('tests.sessions');
});
