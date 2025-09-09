<?php

namespace App\Services;

use App\Models\ProductGeoPrice;
use App\Models\PriceCoefficient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PriceService
{
    /**
     * Get cached final price for product+geo.
     * Cached for 10 minutes (600 seconds).
     */
    public function getFinalPrice(int $productId, int $geoId)
    {
        try {
            $key = "price:{$productId}:{$geoId}";

            return Cache::remember($key, 600, function () use ($productId, $geoId) {
                $pg = ProductGeoPrice::where('product_id', $productId)
                    ->where('geo_id', $geoId)
                    ->first();

                if (! $pg) {
                    return null;
                }

                $coef = PriceCoefficient::where('product_id', $productId)
                    ->where('geo_id', $geoId)
                    ->latest()
                    ->first();

                $coefficient = $coef->coefficient ?? 1.0;

                // final price formula
                $final = ($pg->base_price_local + $pg->delivery_cost) * floatval($coefficient);

                return round($final, 2);
            });
        } catch (\Exception $e) {
            Log::error("PriceService::getFinalPrice error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Invalidate cache for product+geo.
     */
    public function invalidateCache(int $productId, int $geoId)
    {
        try {
            $key = "price:{$productId}:{$geoId}";
            Cache::forget($key);
            return true;
        } catch (\Exception $e) {
            Log::error("PriceService::invalidateCache error: " . $e->getMessage());
            return false;
        }
    }
}
