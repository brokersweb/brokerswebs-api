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
        Schema::create('configuration_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('authorization_number');
            $table->date('authorization_date');
            // Fecha de expedición
            $table->string('date_issue');
            $table->string('prefix');
            $table->string('start_number');
            $table->string('end_number');
            // Vigencia
            $table->string('validity');
            // CUFE
            $table->string('cufe');
            $table->integer('vat');
            $table->integer('retention');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_invoices');
    }
};
