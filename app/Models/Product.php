<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Product extends Model
{

    use HasFactory;

    protected $fillable = ['name','base_price', 'category_id'];

    public function geoPrices()
    {
        try {
            return $this->hasMany(ProductGeoPrice::class);
        } catch (\Exception $e) {
            Log::error("Product::geoPrices error: " . $e->getMessage());
            return null;
        }
    }

    public function coefficients()
    {
        try {
            return $this->hasMany(PriceCoefficient::class);
        } catch (\Exception $e) {
            Log::error("Product::coefficients error: " . $e->getMessage());
            return null;
        }
    }

    public function category()
    {
        try {
            return $this->belongsTo(Category::class);
        } catch (\Exception $e) {
            Log::error("Product->category relation error: " . $e->getMessage());
            return null;
        }
    }

    public function images()
    {
        try {
            return $this->hasMany(Image::class);
        } catch (\Exception $e) {
            Log::error("Product->images relation error: " . $e->getMessage());
            return collect();
        }
    }
}
