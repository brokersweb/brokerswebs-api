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
        Schema::create('inventory_entrance_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_entrance_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->string('name')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->bigInteger('qty')->default(1);
            $table->decimal('total', 12, 2)->nullable();
            $table->integer('type')->default(1)->comment('1. Material, 2. Herramienta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_entrance_items');
    }
};
