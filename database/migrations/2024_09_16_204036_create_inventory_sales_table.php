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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->nullable()->constrained('clients');
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants');
            $table->foreignUuid('immovable_id')->nullable()->constrained('immovables');
            $table->string('serial')->unique();
            $table->text('observation')->nullable();
            $table->enum('status', ['created', 'send', 'paid', 'partially_paid', 'overdue', 'cancelled'])->default('created');
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
