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
        Schema::create('rental_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('rentalnum')->nullable()->unique();
            $table->foreignUuid('immovable_id')->constrained();
            $table->foreignUuid('tenant_id')->constrained();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'overdue'])->default('active');
            $table->double('rent_price', 12, 2);
            $table->integer('cutoff_day');
            $table->longText('path_file')->nullable();
            $table->foreignUuid('cosigner_id')->constrained('cosigners');
            $table->foreignUuid('cosignerii_id')->nullable()->constrained('cosigners');
            $table->foreignUuid('reference_id')->nullable()->constrained('references');
            $table->foreignUuid('referenceii_id')->nullable()->constrained('references');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_contracts');
    }
};
