<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error("Category index error: " . $e->getMessage());
            return back()->with('error', 'Could not load categories.');
        }
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name',
                'image' => 'nullable|image|max:51200',
            ]);

            $category = Category::create([
                'name' => $request->name,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images');
                $hash = substr(md5_file(storage_path('app/' . $path)), 0, 16);

                Image::create([
                    'path' => $path,
                    'hash' => $hash,
                    'category_id' => $category->id,
                ]);
            }

            return redirect()->route('categories.index')->with('success', 'Category created with image.');
        } catch (\Exception $e) {
            Log::error("Category store error: " . $e->getMessage());
            return back()->with('error', 'Could not create category.');
        }
    }


    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id,
                'image' => 'nullable|image|max:51200', 
            ]);

            $category->update(['name' => $request->name]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images');
                $hash = substr(md5_file(storage_path('app/' . $path)), 0, 16);

                $existingImage = $category->images()->first();

                if ($existingImage) {
                    Storage::delete($existingImage->path);
                    $existingImage->update([
                        'path' => $path,
                        'hash' => $hash,
                    ]);
                } else {
                    Image::create([
                        'path' => $path,
                        'hash' => $hash,
                        'category_id' => $category->id,
                    ]);
                }
            }

            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Log::error("Category update error: " . $e->getMessage());
            return back()->with('error', 'Could not update category.');
        }
    }


    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted.');
        } catch (\Exception $e) {
            Log::error("Category delete error: " . $e->getMessage());
            return back()->with('error', 'Could not delete category.');
        }
    }
}
