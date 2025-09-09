<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class ProductGeoPrice extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','geo_id','delivery_cost','base_price_local'];

    public function product()
    {
        try {
            return $this->belongsTo(Product::class);
        } catch (\Exception $e) {
            Log::error("ProductGeoPrice::product error: " . $e->getMessage());
            return null;
        }
    }

    public function geo()
    {
        try {
            return $this->belongsTo(Geo::class);
        } catch (\Exception $e) {
            Log::error("ProductGeoPrice::geo error: " . $e->getMessage());
            return null;
        }
    }
}
