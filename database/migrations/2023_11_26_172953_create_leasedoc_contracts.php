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
        Schema::create('leasedoc_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('tenant_id')->constrained('tenants');
            $table->foreignUuid('immovable_id')->constrained('immovables');
            // Inquilino
            $table->string('tenant_name');
            $table->string('tenant_dni');
            $table->string('tenant_dni_expedition');
            $table->string('tenant_dni_expedition_place');
            // Inmueble
            $table->string('immovable_address');
            $table->string('immovable_rent_price');
            $table->integer('immovable_duration_rent');
            $table->string('immovable_start_contract');
            $table->string('immovable_end_contract');
            $table->integer('immovable_nmonths_contract');
            // Codeudores Info Codeudores, nombre, cedula, lugar exp
            $table->json('cosigner');
            // Document
            $table->longText('document_path');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leasedoc_contracts');
    }
};
