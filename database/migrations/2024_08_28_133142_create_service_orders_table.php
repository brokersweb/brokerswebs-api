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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('assigned_id')->constrained('users');
            $table->foreignUuid('client_id')->nullable();
            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants');
            $table->foreignUuid('immovable_id')->nullable()->constrained('immovables');
            $table->enum('status', ['opened', 'in_progress', 'completed','cancelled'])->default('opened');
            $table->text('comment')->nullable();
            $table->date('start_date');
            $table->time('start_time');
            $table->timestamps();
        });

        Schema::create('service_order_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_order_id')->constrained();
            $table->text('description');
            $table->bigInteger('qty');
            $table->double('price', 12, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('service_order_services');

    }
};
