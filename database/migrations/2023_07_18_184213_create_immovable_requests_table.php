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
        Schema::create('immovable_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->string('phone');
            $table->enum('type', ['visit', 'financiation']);
            $table->foreignUuid('immovable_id')->constrained('immovables');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->string('terms')->comment('1: Acepta, 0: No acepta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immovable_requests');
    }
};
