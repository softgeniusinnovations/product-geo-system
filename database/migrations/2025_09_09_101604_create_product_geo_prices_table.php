<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_geo_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('geo_id')->constrained('geos')->onDelete('cascade');
            $table->decimal('delivery_cost', 10, 2)->default(0.00);
            $table->decimal('base_price_local', 12, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['product_id','geo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_geo_prices');
    }
};
