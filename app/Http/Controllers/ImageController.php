<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function uploadForm()
    {
        return view('images.upload');
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|file|image|max:51200',
            ]);

            $file = $request->file('image');
            $path = $file->store('images');

            $fullPath = storage_path('app/' . $path);
            $hash = hash_file('sha256', $fullPath);

            $similar = Image::where('hash', $hash)->first();

            $categoryId = $similar ? $similar->category_id : null;

            $image = Image::create([
                'path' => $path,
                'hash' => $hash,
                'category_id' => $categoryId,
                'meta' => null,
            ]);

            if (class_exists(\App\Jobs\ProcessImageHashJob::class)) {
                \App\Jobs\ProcessImageHashJob::dispatch($image->id);
            }

            return response()->json(['image' => $image], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("ImageController::store error: " . $e->getMessage());
            return response()->json(['error' => 'Upload failed'], 500);
        }
    }
}
