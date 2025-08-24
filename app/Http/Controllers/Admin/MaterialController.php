<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::with('test')->latest()->paginate(20);
        return view('admin.materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $test = Test::findOrFail($request->test);
        return view('admin.materials.create', compact('test'));
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
            'type' => ['required', Rule::in(['audio', 'text', 'image'])],
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file',
            'order' => 'nullable|integer|min:1',
            'metadata' => 'nullable|string',
        ]);

        // Validate file upload for audio and image types
        if (in_array($validated['type'], ['audio', 'image'])) {
            $request->validate([
                'file' => 'required|file',
            ]);

            if ($validated['type'] === 'audio') {
                $request->validate([
                    'file' => 'mimes:mp3,wav,m4a,aac|max:51200', // 50MB max
                ]);
            } else {
                $request->validate([
                    'file' => 'mimes:jpeg,jpg,png,gif|max:10240', // 10MB max
                ]);
            }
        }

        // Validate content for text type
        if ($validated['type'] === 'text') {
            $request->validate([
                'content' => 'required|string',
            ]);
        }

        $materialData = [
            'test_id' => $validated['test_id'],
            'module' => $validated['module'],
            'part' => $validated['part'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'order' => $validated['order'] ?? 1,
            'metadata' => $validated['metadata'] ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');

            $materialData['file_path'] = $filePath;
            $materialData['file_name'] = $file->getClientOriginalName();
            $materialData['file_size'] = $file->getSize();
            $materialData['mime_type'] = $file->getMimeType();
        }

        $material = Material::create($materialData);

        return redirect()->route('admin.tests.show', $validated['test_id'])
            ->with('success', 'Material added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        $material->load('test');
        return view('admin.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $material->load('test');
        return view('admin.materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'module' => ['required', Rule::in(['listening', 'reading', 'writing'])],
            'part' => 'required|integer|min:1|max:4',
            'type' => ['required', Rule::in(['audio', 'text', 'image'])],
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file',
            'order' => 'nullable|integer|min:1',
            'metadata' => 'nullable|string',
        ]);

        // Validate file upload for audio and image types
        if (in_array($validated['type'], ['audio', 'image'])) {
            if ($validated['type'] === 'audio') {
                $request->validate([
                    'file' => 'nullable|mimes:mp3,wav,m4a,aac|max:51200', // 50MB max
                ]);
            } else {
                $request->validate([
                    'file' => 'nullable|mimes:jpeg,jpg,png,gif|max:10240', // 10MB max
                ]);
            }
        }

        // Validate content for text type
        if ($validated['type'] === 'text') {
            $request->validate([
                'content' => 'required|string',
            ]);
        }

        $materialData = [
            'module' => $validated['module'],
            'part' => $validated['part'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'order' => $validated['order'] ?? 1,
            'metadata' => $validated['metadata'] ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');

            $materialData['file_path'] = $filePath;
            $materialData['file_name'] = $file->getClientOriginalName();
            $materialData['file_size'] = $file->getSize();
            $materialData['mime_type'] = $file->getMimeType();
        }

        $material->update($materialData);

        return redirect()->route('admin.tests.show', $material->test_id)
            ->with('success', 'Material updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $testId = $material->test_id;

        // Delete file if exists
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('admin.tests.show', $testId)
            ->with('success', 'Material deleted successfully!');
    }
}
