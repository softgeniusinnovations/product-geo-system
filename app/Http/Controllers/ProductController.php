<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductGeoPrice;
use App\Models\Category;
use App\Models\Geo;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with([
                'category',
                'images',
                'geoPrices.geo',
                'coefficients'
            ])->get();

            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            Log::error("Error loading products: " . $e->getMessage());
            return back()->with('error', 'Could not load products.');
        }
    }


    public function create()
    {
        try {
            $categories = Category::all();
            $geos = Geo::all();
            return view('products.create', compact('categories', 'geos'));
        } catch (\Exception $e) {
            Log::error("Product create page error: " . $e->getMessage());
            return back()->with('error', 'Could not load create page.');
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:products,name',
                'base_price' => 'required|numeric',
                'category_id' => 'required|exists:product_categories,id',
                'image.*' => 'nullable|image|max:51200',
                'geo_id.*' => 'required|exists:geos,id',
                'delivery_cost.*' => 'nullable|numeric',
                'base_price_local.*' => 'nullable|numeric',
            ]);

            $product = Product::create([
                'name' => $request->name,
                'base_price' => $request->base_price,
                'category_id' => $request->category_id,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products');
                    $hash = substr(md5_file(storage_path('app/' . $path)), 0, 16);

                    Image::create([
                        'path' => $path,
                        'hash' => $hash,
                        'product_id' => $product->id,
                        'category_id' => $product->category_id,
                    ]);
                }
            }

            try {
                foreach ($request->geo_id as $index => $geoId) {
                    ProductGeoPrice::create([
                        'product_id' => $product->id,
                        'geo_id' => $geoId,
                        'delivery_cost' => $request->delivery_cost[$index] ?? 0,
                        'base_price_local' => $request->base_price_local[$index] ?? $product->base_price,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Product GEO prices store error: " . $e->getMessage());
                return back()->with('error', 'Product created but GEO prices failed.');
            }

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            Log::error("Product store error: " . $e->getMessage());
            return back()->with('error', 'Could not create product.');
        }
    }


    public function edit(Product $product)
    {
        try {
            $categories = Category::all();
            $geos = Geo::all();
            $geoPrices = $product->geoPrices()->get()->keyBy('geo_id');
            return view('products.edit', compact('product', 'categories', 'geos', 'geoPrices'));
        } catch (\Exception $e) {
            Log::error("Product edit page error: " . $e->getMessage());
            return back()->with('error', 'Could not load edit page.');
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|unique:products,name,' . $product->id,
                'base_price' => 'required|numeric',
                'category_id' => 'required|exists:product_categories,id',
                'images.*' => 'nullable|image|max:51200',
                'geo_id.*' => 'required|exists:geos,id',
                'delivery_cost.*' => 'nullable|numeric',
                'base_price_local.*' => 'nullable|numeric',
            ]);

            $product->update([
                'name' => $request->name,
                'base_price' => $request->base_price,
                'category_id' => $request->category_id,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products');
                    $hash = substr(md5_file(storage_path('app/' . $path)), 0, 16);

                    Image::create([
                        'path' => $path,
                        'hash' => $hash,
                        'product_id' => $product->id,
                        'category_id' => $product->category_id,
                    ]);
                }
            }

            try {
                $product->geoPrices()->delete();
                foreach ($request->geo_id as $index => $geoId) {
                    ProductGeoPrice::create([
                        'product_id' => $product->id,
                        'geo_id' => $geoId,
                        'delivery_cost' => $request->delivery_cost[$index] ?? 0,
                        'base_price_local' => $request->base_price_local[$index] ?? $product->base_price,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Product GEO prices update error: " . $e->getMessage());
                return back()->with('error', 'Product updated but GEO prices failed.');
            }

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error("Product update error: " . $e->getMessage());
            return back()->with('error', 'Could not update product.');
        }
    }


    public function destroy(Product $product)
    {
        try {
            $product->images()->each(function ($img) {
                Storage::delete($img->path);
                $img->delete();
            });
            $product->geoPrices()->delete();
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Product deleted.');
        } catch (\Exception $e) {
            Log::error("Product delete error: " . $e->getMessage());
            return back()->with('error', 'Could not delete product.');
        }
    }
}
