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
        Schema::create('tools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->longText('photo')->nullable();
            $table->bigInteger('total_quantity')->default(1);
            $table->bigInteger('available_quantity')->default(1);
            $table->foreignUuid('category_id')->nullable()->constrained();
            $table->enum('status', ['available', 'unavailable', 'out_stock'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
