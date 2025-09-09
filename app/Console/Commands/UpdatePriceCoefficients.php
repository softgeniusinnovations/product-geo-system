<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductGeoPrice;
use App\Models\PriceCoefficient;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PriceService;

class UpdatePriceCoefficients extends Command
{
    protected $signature = 'prices:update-coefficients';
    protected $description = 'Recalculate price coefficients for product+geo based on leads (runs every 10 minutes)';

    public function handle(): int
    {
        try {
            $pairs = ProductGeoPrice::select('product_id','geo_id')->get();

            foreach ($pairs as $p) {
                DB::beginTransaction();
                try {
                    $leadCount = Lead::where('product_id', $p->product_id)
                        ->where('geo_id', $p->geo_id)
                        ->where('created_at', '>=', now()->subDays(7)) 
                        ->count();

                    $coefficientValue = $this->computeCoefficient($leadCount);

                    PriceCoefficient::create([
                        'product_id' => $p->product_id,
                        'geo_id' => $p->geo_id,
                        'coefficient' => $coefficientValue,
                    ]);

                    app(PriceService::class)->invalidateCache($p->product_id, $p->geo_id);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Failed to update coefficient for product {$p->product_id} geo {$p->geo_id}: " . $e->getMessage());
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error("UpdatePriceCoefficients::handle error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function computeCoefficient(int $leads): float
    {
        try {
            if ($leads <= 0) return 1.0;

            $reduction = log10(1 + $leads) * 0.05;
            $coef = 1.0 - $reduction;
            $coef = max(0.6, min(1.2, $coef));
            return round($coef, 4);
        } catch (\Exception $e) {
            Log::error("computeCoefficient error: " . $e->getMessage());
            return 1.0;
        }
    }
}
