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
        Schema::create('freelancers_professional_type', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('entity'); // Codeudor o Solicitante
            $table->string('name'); //Nombre de la empresa
            $table->string('phone'); //Número de teléfono
            $table->string('address'); //Dirección de la empresa
            $table->string('country')->default('Colombia');
            $table->string('department');
            $table->string('city');
            // Descripcion del trabajo
            $table->longText('description');
            $table->string('income')->comment('Ingresos mes');
            $table->string('expense')->comment('Egresos mes');
            // Certificado contador público
            $table->longText('caccount_public')->comment('Cert. Contador público');
            $table->longText('rut_file')->comment('RUT');
            $table->longText('bank_statement')->comment('Estracto bancario');
            $table->longText('income_statement')->comment('Declaración de renta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancers_professional_type');
    }
};
