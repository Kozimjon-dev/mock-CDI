# IELTS Mock CDI Platform Completion Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete all missing features of the IELTS Mock CDI platform — admin auth, missing views, results/sessions pages, public test pages, and score display.

**Architecture:** Laravel 12 with Blade views, Tailwind CSS, Alpine.js. Simple session-based admin auth using the existing User model. All views follow the established pattern in `resources/views/admin/tests/`.

**Tech Stack:** PHP 8.2+, Laravel 12, Tailwind CSS 4.0, Alpine.js, MySQL

---

### Task 1: Admin Authentication — Middleware & Controller

**Files:**
- Create: `app/Http/Middleware/AdminAuth.php`
- Create: `app/Http/Controllers/Admin/AuthController.php`
- Modify: `bootstrap/app.php`
- Modify: `routes/web.php`

- [ ] **Step 1: Create AdminAuth middleware**

```php
// app/Http/Middleware/AdminAuth.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        return $next($request);
    }
}
```

- [ ] **Step 2: Register middleware alias in bootstrap/app.php**

Add the middleware alias inside `withMiddleware`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminAuth::class,
    ]);
})
```

- [ ] **Step 3: Create AuthController**

```php
// app/Http/Controllers/Admin/AuthController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
```

- [ ] **Step 4: Update routes/web.php — add auth routes and protect admin routes**

Replace the admin routes block with:

```php
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// Admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin protected routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
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
```

---

### Task 2: Admin Login View & Admin Layout

**Files:**
- Create: `resources/views/admin/auth/login.blade.php`
- Create: `resources/views/layouts/admin.blade.php`
- Modify: `resources/views/admin/tests/index.blade.php` (change extends to admin layout)

- [ ] **Step 1: Create admin login view**

```blade
{{-- resources/views/admin/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Admin Panel</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Sign in to manage your IELTS tests</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('email') border-red-300 @enderror"
                           placeholder="Email address" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Password">
                </div>
            </div>

            @error('email')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
```

- [ ] **Step 2: Create admin layout with sidebar navigation**

```blade
{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MOCK CDI IELTS') }} - @yield('title', 'Admin')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">MOCK CDI</a>
                <p class="text-gray-400 text-xs mt-1">Admin Panel</p>
            </div>
            <nav class="mt-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-6 py-3 text-sm {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.tests.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Tests
                </a>
                <a href="{{ route('admin.materials.index') }}"
                   class="flex items-center px-6 py-3 text-sm {{ request()->routeIs('admin.materials.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Materials
                </a>
                <a href="{{ route('admin.questions.index') }}"
                   class="flex items-center px-6 py-3 text-sm {{ request()->routeIs('admin.questions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Questions
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white" title="Logout">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700" target="_blank">View Site</a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-md p-4">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
```

- [ ] **Step 3: Update all existing admin views to extend layouts.admin**

Change `@extends('layouts.app')` to `@extends('layouts.admin')` in:
- `admin/tests/index.blade.php`
- `admin/tests/create.blade.php`
- `admin/tests/edit.blade.php`
- `admin/tests/show.blade.php`
- `admin/materials/create.blade.php`
- `admin/questions/create.blade.php`

---

### Task 3: Admin Seeder — Create Default Admin User

**Files:**
- Create: `database/seeders/AdminSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Create AdminSeeder**

```php
// database/seeders/AdminSeeder.php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mockcdi.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
```

- [ ] **Step 2: Update DatabaseSeeder to call AdminSeeder**

```php
public function run(): void
{
    $this->call([
        AdminSeeder::class,
        TestSeeder::class,
    ]);
}
```

---

### Task 4: Materials Admin Views — Index, Show, Edit

**Files:**
- Create: `resources/views/admin/materials/index.blade.php`
- Create: `resources/views/admin/materials/show.blade.php`
- Create: `resources/views/admin/materials/edit.blade.php`

- [ ] **Step 1: Create materials index view**

```blade
{{-- resources/views/admin/materials/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Materials')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">All Materials</h2>
            <p class="mt-1 text-gray-600">Manage test materials across all tests.</p>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($materials->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Part</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($materials as $material)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $material->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('admin.tests.show', $material->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $material->test->title }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $material->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($material->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($material->module) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($material->type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Part {{ $material->part }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                        <a href="{{ route('admin.materials.show', $material) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <a href="{{ route('admin.materials.edit', $material) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                        <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this material?')" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($materials->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $materials->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No materials found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
```

- [ ] **Step 2: Create materials show view**

```blade
{{-- resources/views/admin/materials/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Material Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $material->title }}</h2>
            <p class="mt-1 text-gray-600">Part of: <a href="{{ route('admin.tests.show', $material->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $material->test->title }}</a></p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.materials.edit', $material) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Edit</a>
            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this material?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Material Information</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Module</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($material->module) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($material->type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Part</dt>
                    <dd class="mt-1 text-sm text-gray-900">Part {{ $material->part }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Order</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $material->order }}</dd>
                </div>
                @if($material->file_name)
                <div>
                    <dt class="text-sm font-medium text-gray-500">File</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $material->file_name }} ({{ number_format($material->file_size / 1024, 1) }} KB)</dd>
                </div>
                @endif
            </dl>

            @if($material->content)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Content</dt>
                <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4 whitespace-pre-wrap">{{ $material->content }}</dd>
            </div>
            @endif

            @if($material->type === 'audio' && $material->file_path)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Audio Preview</dt>
                <audio controls class="w-full">
                    <source src="{{ Storage::url($material->file_path) }}" type="{{ $material->mime_type }}">
                </audio>
            </div>
            @endif

            @if($material->type === 'image' && $material->file_path)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Image Preview</dt>
                <img src="{{ Storage::url($material->file_path) }}" alt="{{ $material->title }}" class="max-w-lg rounded-lg shadow">
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
```

- [ ] **Step 3: Create materials edit view**

```blade
{{-- resources/views/admin/materials/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Material')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Edit Material</h2>
        <p class="mt-1 text-gray-600">Editing "{{ $material->title }}" from {{ $material->test->title }}</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.materials.update', $material) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 space-y-6">
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700">Module *</label>
                    <select name="module" id="module" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="listening" {{ old('module', $material->module) == 'listening' ? 'selected' : '' }}>Listening</option>
                        <option value="reading" {{ old('module', $material->module) == 'reading' ? 'selected' : '' }}>Reading</option>
                        <option value="writing" {{ old('module', $material->module) == 'writing' ? 'selected' : '' }}>Writing</option>
                    </select>
                    @error('module') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="part" class="block text-sm font-medium text-gray-700">Part *</label>
                    <select name="part" id="part" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ old('part', $material->part) == $i ? 'selected' : '' }}>Part {{ $i }}</option>
                        @endfor
                    </select>
                    @error('part') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                    <select name="type" id="type" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="audio" {{ old('type', $material->type) == 'audio' ? 'selected' : '' }}>Audio</option>
                        <option value="text" {{ old('type', $material->type) == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="image" {{ old('type', $material->type) == 'image' ? 'selected' : '' }}>Image</option>
                    </select>
                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $material->title) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="8" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('content', $material->content) }}</textarea>
                    @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Replace File</label>
                    @if($material->file_name)
                        <p class="text-sm text-gray-500 mb-2">Current: {{ $material->file_name }}</p>
                    @endif
                    <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="order" id="order" min="1" value="{{ old('order', $material->order) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.tests.show', $material->test) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update Material</button>
            </div>
        </form>
    </div>
</div>
@endsection
```

---

### Task 5: Questions Admin Views — Index, Show, Edit

**Files:**
- Create: `resources/views/admin/questions/index.blade.php`
- Create: `resources/views/admin/questions/show.blade.php`
- Create: `resources/views/admin/questions/edit.blade.php`

- [ ] **Step 1: Create questions index view**

```blade
{{-- resources/views/admin/questions/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Questions')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">All Questions</h2>
        <p class="mt-1 text-gray-600">Manage test questions across all tests.</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($questions->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($questions as $question)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ Str::limit($question->question_text, 60) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('admin.tests.show', $question->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $question->test->title }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $question->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($question->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($question->module) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $question->points }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                        <a href="{{ route('admin.questions.show', $question) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <a href="{{ route('admin.questions.edit', $question) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this question?')" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($questions->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $questions->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No questions found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
```

- [ ] **Step 2: Create questions show view**

```blade
{{-- resources/views/admin/questions/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Question Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Question Details</h2>
            <p class="mt-1 text-gray-600">From: <a href="{{ route('admin.tests.show', $question->test) }}" class="text-indigo-600 hover:text-indigo-900">{{ $question->test->title }}</a></p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Edit</a>
            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this question?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Question Information</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Module</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $question->module === 'listening' ? 'bg-purple-100 text-purple-800' : ($question->module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($question->module) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Part</dt>
                    <dd class="mt-1 text-sm text-gray-900">Part {{ $question->part }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Points</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $question->points }}</dd>
                </div>
            </dl>

            <div class="mb-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Question Text</dt>
                <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4">{{ $question->question_text }}</dd>
            </div>

            @if(!empty($question->options))
            <div class="mb-6">
                <dt class="text-sm font-medium text-gray-500 mb-2">Options</dt>
                <dd>
                    <ul class="space-y-1">
                        @foreach($question->options as $i => $option)
                        <li class="text-sm text-gray-900 flex items-center">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-xs font-medium text-gray-700 mr-2">{{ chr(65 + $i) }}</span>
                            {{ $option }}
                            @if(in_array($option, $question->correct_answers))
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Correct</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </dd>
            </div>
            @endif

            <div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Correct Answers</dt>
                <dd class="flex flex-wrap gap-2">
                    @foreach($question->correct_answers as $answer)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">{{ $answer }}</span>
                    @endforeach
                </dd>
            </div>
        </div>
    </div>
</div>
@endsection
```

- [ ] **Step 3: Create questions edit view**

```blade
{{-- resources/views/admin/questions/edit.blade.php --}}
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
```

---

### Task 6: Test Results & Sessions Views

**Files:**
- Create: `resources/views/admin/tests/results.blade.php`
- Create: `resources/views/admin/tests/sessions.blade.php`

- [ ] **Step 1: Create test results view**

```blade
{{-- resources/views/admin/tests/results.blade.php --}}
@extends('layouts.admin')

@section('title', 'Test Results')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Results: {{ $test->title }}</h2>
        <p class="mt-1 text-gray-600">Performance analytics for completed test sessions.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Total Sessions</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_sessions'] }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_students'] }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Avg Listening Score</dt>
            <dd class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['avg_listening_score'] }}%</dd>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <dt class="text-sm font-medium text-gray-500 truncate">Avg Reading Score</dt>
            <dd class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['avg_reading_score'] }}%</dd>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Completed Sessions</h3>
        </div>

        @if($sessions->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Listening</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reading</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cheating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($sessions as $session)
                @php
                    $listeningCorrect = $session->studentResponses->where('module', 'listening')->where('is_correct', true)->count();
                    $listeningTotal = $test->listeningQuestions()->count();
                    $readingCorrect = $session->studentResponses->where('module', 'reading')->where('is_correct', true)->count();
                    $readingTotal = $test->readingQuestions()->count();
                    $totalCorrect = $listeningCorrect + $readingCorrect;
                    $totalQuestions = $listeningTotal + $readingTotal;
                @endphp
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $session->student->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $session->student->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="{{ $listeningTotal > 0 && ($listeningCorrect / $listeningTotal) >= 0.7 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $listeningCorrect }}/{{ $listeningTotal }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="{{ $readingTotal > 0 && ($readingCorrect / $readingTotal) >= 0.7 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $readingCorrect }}/{{ $readingTotal }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $totalCorrect }}/{{ $totalQuestions }}
                        @if($totalQuestions > 0)
                            ({{ round(($totalCorrect / $totalQuestions) * 100) }}%)
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($session->has_cheated)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Detected</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Clean</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->completed_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($sessions->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $sessions->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No completed sessions yet.</p>
        </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.tests.show', $test) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to Test</a>
    </div>
</div>
@endsection
```

- [ ] **Step 2: Create test sessions view**

```blade
{{-- resources/views/admin/tests/sessions.blade.php --}}
@extends('layouts.admin')

@section('title', 'Test Sessions')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Sessions: {{ $test->title }}</h2>
        <p class="mt-1 text-gray-600">All test sessions (active and completed).</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($sessions->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cheating</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($sessions as $session)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $session->student->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $session->student->email ?? $session->student->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $session->current_module === 'completed' ? 'bg-green-100 text-green-800' :
                               ($session->current_module === 'listening' ? 'bg-purple-100 text-purple-800' :
                               ($session->current_module === 'reading' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($session->current_module) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($session->completed_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In Progress</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($session->has_cheated)
                            @php $cheats = json_decode($session->cheat_attempts, true) ?? []; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ count($cheats) }} attempt(s)</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Clean</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($sessions->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $sessions->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No sessions found for this test.</p>
        </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.tests.show', $test) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to Test</a>
    </div>
</div>
@endsection
```

---

### Task 7: Public Test Pages — Tests Index & Show

**Files:**
- Create: `resources/views/tests/index.blade.php`
- Create: `resources/views/tests/show.blade.php`

- [ ] **Step 1: Create public tests index view**

```blade
{{-- resources/views/tests/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Available Tests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Available IELTS Mock Tests</h1>
        <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">Choose a test and start practicing.</p>
    </div>

    @if($tests->count() > 0)
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($tests as $test)
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900">{{ $test->title }}</h3>
                </div>

                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($test->description, 120) }}</p>

                <div class="flex items-center text-xs text-gray-500 space-x-3 mb-4">
                    <span>Listening: {{ $test->listening_time }}m</span>
                    <span>Reading: {{ $test->reading_time }}m</span>
                    <span>Writing: {{ $test->writing_time }}m</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Total: {{ $test->total_time }} min</span>
                    <a href="{{ route('test.show', $test) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($tests->hasPages())
    <div class="mt-8">
        {{ $tests->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-16">
        <p class="text-lg text-gray-500">No tests available at the moment. Check back later.</p>
    </div>
    @endif
</div>
@endsection
```

- [ ] **Step 2: Create public test show/detail view**

```blade
{{-- resources/views/tests/show.blade.php --}}
@extends('layouts.app')

@section('title', $test->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-indigo-600 px-6 py-8">
            <h1 class="text-3xl font-bold text-white">{{ $test->title }}</h1>
            <p class="mt-2 text-indigo-200">IELTS Mock Test</p>
        </div>

        <div class="px-6 py-6">
            <p class="text-gray-700 mb-6">{{ $test->description }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-purple-700">{{ $test->listening_time }} min</div>
                    <div class="text-sm text-purple-600">Listening</div>
                    <div class="text-xs text-purple-500 mt-1">{{ $test->listeningQuestions()->count() }} questions</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700">{{ $test->reading_time }} min</div>
                    <div class="text-sm text-blue-600">Reading</div>
                    <div class="text-xs text-blue-500 mt-1">{{ $test->readingQuestions()->count() }} questions</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-700">{{ $test->writing_time }} min</div>
                    <div class="text-sm text-green-600">Writing</div>
                    <div class="text-xs text-green-500 mt-1">{{ $test->writingQuestions()->count() }} tasks</div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-yellow-800 mb-2">Before you start:</h3>
                <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                    <li>Total test time: {{ $test->total_time }} minutes</li>
                    <li>Test runs in fullscreen mode — do not exit</li>
                    <li>Switching tabs or windows will be recorded</li>
                    <li>Copy/paste and keyboard shortcuts are disabled</li>
                    <li>Complete all modules: Listening, Reading, Writing</li>
                </ul>
            </div>

            <div class="text-center">
                <a href="{{ route('student.register', $test) }}"
                   class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                    Register & Start Test
                </a>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('tests') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Back to All Tests</a>
    </div>
</div>
@endsection
```

---

### Task 8: Student Completed Page — Add Score Display

**Files:**
- Modify: `resources/views/student/completed.blade.php`
- Modify: `app/Http/Controllers/Student/SessionController.php`

- [ ] **Step 1: Update SessionController::show to pass score data**

In the `show` method, add score calculation before returning the completed view:

```php
public function show(string $sessionToken)
{
    $session = $this->getSession($sessionToken);

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
```

- [ ] **Step 2: Update completed.blade.php to show scores**

Replace the "Module Completion" section in the completed view with actual scores:

```blade
<!-- Score Results -->
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-3">Your Results</h3>
    <div class="space-y-3">
        <div class="flex items-center justify-between bg-purple-50 rounded-lg p-3">
            <span class="text-sm font-medium text-purple-700">Listening</span>
            <span class="text-sm font-bold text-purple-900">{{ $scores['listening']['correct'] }}/{{ $scores['listening']['total'] }}
                @if($scores['listening']['total'] > 0) ({{ round(($scores['listening']['correct'] / $scores['listening']['total']) * 100) }}%) @endif
            </span>
        </div>
        <div class="flex items-center justify-between bg-blue-50 rounded-lg p-3">
            <span class="text-sm font-medium text-blue-700">Reading</span>
            <span class="text-sm font-bold text-blue-900">{{ $scores['reading']['correct'] }}/{{ $scores['reading']['total'] }}
                @if($scores['reading']['total'] > 0) ({{ round(($scores['reading']['correct'] / $scores['reading']['total']) * 100) }}%) @endif
            </span>
        </div>
        <div class="flex items-center justify-between bg-green-50 rounded-lg p-3">
            <span class="text-sm font-medium text-green-700">Writing</span>
            <span class="text-sm text-green-800">Submitted (reviewed by instructor)</span>
        </div>
        <div class="flex items-center justify-between bg-indigo-50 rounded-lg p-3 border-2 border-indigo-200">
            <span class="text-sm font-bold text-indigo-700">Overall Score</span>
            <span class="text-lg font-bold text-indigo-900">{{ $scores['overall']['correct'] }}/{{ $scores['overall']['total'] }}
                @if($scores['overall']['total'] > 0) ({{ round(($scores['overall']['correct'] / $scores['overall']['total']) * 100) }}%) @endif
            </span>
        </div>
    </div>
</div>
```

---

### Task 9: Update Navigation — Add Admin Login Link

**Files:**
- Modify: `resources/views/layouts/app.blade.php`

- [ ] **Step 1: Update app layout navigation**

Add admin/login link to the public navigation bar:

```blade
<div class="hidden sm:ml-6 sm:flex sm:space-x-8">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
        Home
    </a>
    <a href="{{ route('tests') }}" class="{{ request()->routeIs('tests') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
        Tests
    </a>
</div>
```

And on the right side of the navbar, add:

```blade
<div class="flex items-center">
    @auth
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 mr-4">Admin Panel</a>
        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
        </form>
    @else
        <a href="{{ route('admin.login') }}" class="text-sm text-gray-500 hover:text-gray-700">Admin Login</a>
    @endauth
</div>
```