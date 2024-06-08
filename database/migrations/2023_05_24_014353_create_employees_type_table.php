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
        Schema::create('employees_type', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('entity'); // Codeudor o Solicitante
            $table->string('name'); //Nombre de la empresa');
            $table->string('phone'); //Número de teléfono');
            $table->string('address'); //Dirección de la empresa');
            $table->string('country')->default('Colombia');
            $table->string('department');
            $table->string('city');
            // Mercado o sector al que pertenece la empresa
            $table->string('market')->nullable();
            $table->string('salary');
            $table->string('expense');
            $table->date('entry_date');
            $table->longText('working_letter')->comment('Carta laboral');
            $table->json('payment_stubs')->comment('3 Colillas de pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_type');
    }
};
