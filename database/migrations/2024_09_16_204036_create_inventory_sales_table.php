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
        Schema::create('inventory_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_client_id')->constrained('inventory_clients');
            $table->foreignUuid('user_id')->constrained();
            $table->string('serial')->unique();
            $table->text('observation')->nullable();
            $table->date('sale_date');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_sales');
    }
};
