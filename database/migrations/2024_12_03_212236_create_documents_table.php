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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Codeudor o Solicitante
            $table->nullableUuidMorphs('ownerable');
            // Carta laboral.
            $table->longText('letter_employment')->nullable();
            // Certificado de tradición y libertad.
            $table->longText('tradition_freedom')->nullable();
            // Fotocopia del Rut.
            $table->longText('rut')->nullable();
            // Certificado de Cámara y comercio.
            $table->longText('chamber_commerce')->nullable();
            // Fotocopia de la última declaración de renta.
            $table->longText('last_taxreturn')->nullable();
            // Estados financieros.
            $table->longText('financial_statements')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
