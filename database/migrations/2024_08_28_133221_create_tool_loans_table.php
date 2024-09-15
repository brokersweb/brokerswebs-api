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
        Schema::create('tool_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_order_id')->nullable()->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('assigned_id')->constrained('users');
            $table->dateTime('loan_date');
            $table->dateTime('expected_return_date');
            $table->dateTime('actual_return_date')->nullable();
            $table->enum('status', ['loaned', 'returned', 'expired'])->default('loaned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('tool_loan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tool_loan_id')->constrained();
            $table->foreignUuid('tool_id')->constrained();
            $table->bigInteger('qty')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_loans');
        Schema::dropIfExists('tool_loan_details');
    }
};
