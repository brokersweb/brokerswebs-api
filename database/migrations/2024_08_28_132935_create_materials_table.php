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
            $table->decimal('price_basic', 10, 2)->nullable();
            $table->bigInteger('stock')->default(1);
            $table->foreignUuid('category_id')->nullable();
            $table->string('unit')->nullable()->default('und');
            $table->longText('photo')->nullable();
            $table->string('conditions')->nullable()->default('new'); // new , used
            $table->enum('operative_status', ['active', 'inactive','notfound'])->nullable()->default('active'); // new , used
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
