<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function images()
    {
        try {
            return $this->hasMany(Image::class);
        } catch (\Exception $e) {
            Log::error("Product::image error: " . $e->getMessage());
            return null;
        }
    }

    public function products()
    {
        try {
            return $this->hasMany(Product::class);
        } catch (\Exception $e) {
            Log::error("Product::category error: " . $e->getMessage());
            return null;
        }
    }
}
