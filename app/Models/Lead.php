<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','geo_id','customer_name','meta'];

    protected $casts = [
        'meta' => 'array'
    ];

    public function product()
    {
        try {
            return $this->belongsTo(Product::class);
        } catch (\Exception $e) {
            Log::error("Lead::product error: " . $e->getMessage());
            return null;
        }
    }

    public function geo()
    {
        try {
            return $this->belongsTo(Geo::class);
        } catch (\Exception $e) {
            Log::error("Lead::geo error: " . $e->getMessage());
            return null;
        }
    }
}
