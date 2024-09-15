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
        Schema::create('material_returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('return_number')->unique();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('user_return_id')->constrained('users');
            $table->foreignUuid('service_order_id')->nullable()->constrained();
            $table->dateTime('return_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('material_return_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('material_return_id')->constrained();
            $table->foreignUuid('material_id')->constrained();
            $table->bigInteger('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_returns');
        Schema::dropIfExists('material_return_items');
    }
};
