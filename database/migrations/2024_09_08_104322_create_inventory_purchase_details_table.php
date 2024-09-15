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
        Schema::create('inventory_purchase_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_purchase_id')->constrained();
            $table->uuidMorphs('material'); // Material y Herramienta
            $table->bigInteger('qty');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_purchase_details');
    }
};
