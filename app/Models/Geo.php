<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Geo extends Model
{
    use HasFactory;

    protected $fillable = ['country','currency'];

    public function productPrices()
    {
        try {
            return $this->hasMany(ProductGeoPrice::class);
        } catch (\Exception $e) {
            Log::error("Geo::productPrices error: " . $e->getMessage());
            return null;
        }
    }
}
