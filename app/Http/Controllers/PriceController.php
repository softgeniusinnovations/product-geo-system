<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PriceService;
use Illuminate\Support\Facades\Log;

class PriceController extends Controller
{
    protected PriceService $prices;

    public function __construct(PriceService $prices)
    {
        $this->prices = $prices;
    }

    /**
     * GET /price?product_id=1&geo_id=2
     */
    public function show(Request $request)
    {
        try {
            $productId = (int)$request->query('product_id');
            $geoId = (int)$request->query('geo_id');

            $price = $this->prices->getFinalPrice($productId, $geoId);

            if ($price === null) {
                return response()->json(['error' => 'Not found'], 404);
            }

            return response()->json(['product_id' => $productId, 'geo_id' => $geoId, 'price' => $price]);
        } catch (\Exception $e) {
            Log::error("PriceController::show error: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
