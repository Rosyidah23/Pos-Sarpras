<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', [
            'categories' => Category::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($data);

        return back()->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
        ]);

        $category->update($data);

        return back()->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->reports()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih dipakai laporan.');
        }

        $category->delete();

        return back()->with('status', 'Kategori berhasil dihapus.');
    }
}
