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
            $table->string('order_number')->unique();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('assigned_id')->constrained('users');
            $table->uuidMorphs('client');
            $table->enum('status', ['opened', 'completed'])->default('opened');
            $table->text('description');
            $table->date('start_date');
            $table->time('start_time');
            $table->string('type')->nullable();
            $table->string('location');
            $table->string('client_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
