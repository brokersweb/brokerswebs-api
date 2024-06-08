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
        Schema::create('pensioners_type', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('entity'); // Codeudor o Solicitante
            $table->longText('certificate')->comment('Certificado de pensión');
            $table->json('payment_stubs')->comment('Colillas de pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensioners_type');
    }
};
