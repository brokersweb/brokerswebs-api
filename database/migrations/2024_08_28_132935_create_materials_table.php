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
        Schema::create('materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->bigInteger('stock')->default(1);
            // $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('unit')->nullable()->default('und');
            $table->longText('photo')->nullable();
            $table->foreignUuid('category_id')->nullable()->constrained();
            // $table->foreignUuid('supplier_id')->constrained();
            $table->enum('status', ['available', 'unavailable', 'out_stock'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
