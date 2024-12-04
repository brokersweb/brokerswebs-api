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
        Schema::create('attendance_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('attendance_id')->constrained();
            $table->foreignUuid('staff_id')->constrained('users');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('position')->nullable();
            $table->string('worksite')->nullable()->comment('Obra');
            $table->double('payment')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_items');
    }
};
