<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    // Membuatkategori
    public function create() {
        //
        return view('admin.categories.create');
    }

    // Tambah Kategori
    public function store(Request $request) {
        //
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    // EDIT kategori
    public function edit(Category $category) {
        return view('admin.categories.edit', compact('category'));
    }

    // Update kategory
    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus kategori
    public function destroy(Category $category) {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
        
    }
}
