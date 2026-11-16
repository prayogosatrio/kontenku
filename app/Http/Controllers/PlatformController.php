<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlatformRequest;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::withCount('posts')->orderBy('nama_platform')->paginate(10);

        return view('platforms.index', compact('platforms'));
    }

    public function create()
    {
        return view('platforms.create');
    }

    // Validasi gaya 1: Form Request (aturan dipisah ke class sendiri)
    public function store(StorePlatformRequest $request)
    {
        $platform = Platform::create($request->validated());

        return redirect()->route('platforms.index')
            ->with('success', "Platform \"{$platform->nama_platform}\" berhasil ditambahkan.");
    }

    public function edit(string $id)
    {
        $platform = Platform::findOrFail($id);

        return view('platforms.edit', compact('platform'));
    }

    // Validasi gaya 2: inline di Controller (sebagai pembanding dengan Form Request di store)
    public function update(Request $request, string $id)
    {
        $platform = Platform::findOrFail($id);

        $validated = $request->validate([
            'nama_platform' => ['required', 'string', 'max:50', Rule::unique('platforms', 'nama_platform')->ignore($platform->id)],
            'deskripsi' => 'nullable|string',
        ], [
            'nama_platform.required' => 'Nama platform wajib diisi.',
            'nama_platform.max' => 'Nama platform maksimal 50 karakter.',
            'nama_platform.unique' => 'Nama platform sudah terdaftar.',
        ]);

        $platform->update($validated);

        return redirect()->route('platforms.index')
            ->with('success', "Platform \"{$platform->nama_platform}\" berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $platform = Platform::withCount('posts')->findOrFail($id);

        if ($platform->posts_count > 0) {
            return redirect()->route('platforms.index')
                ->with('error', "Platform \"{$platform->nama_platform}\" tidak bisa dihapus karena masih dipakai {$platform->posts_count} post.");
        }

        $platform->delete();

        return redirect()->route('platforms.index')->with('success', 'Platform berhasil dihapus.');
    }
}
