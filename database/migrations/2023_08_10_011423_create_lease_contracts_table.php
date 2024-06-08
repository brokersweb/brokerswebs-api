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
        Schema::create('lease_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('immovable_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('owner_id')->constrained()->onDelete('cascade')->comment('Propietario(Arrendador) del inmueble');
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade')->comment('Arrendatario(Inquilino) titular del inmueble');
            $table->date('start_date')->comment('Fecha de inicio del contrato');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Estado del contrato');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_contracts');
    }
};
