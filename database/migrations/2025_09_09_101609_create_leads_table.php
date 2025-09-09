<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('geo_id')->constrained('geos')->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        // index for counting leads fast
        Schema::table('leads', function (Blueprint $table) {
            $table->index(['product_id','geo_id','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
