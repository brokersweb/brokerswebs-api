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
        Schema::create('readjustments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('residential_unit'); //Unidad residencial');
            $table->string('apt_no'); //Número de apartamento');
            $table->string('tenant_name'); //Nombre del inquilino');
            $table->string('date_visit'); //Fecha de visita');
            $table->decimal('worth', 12, 2)->comment('Valor');
            $table->date('start_contract'); //Fecha de inicio de contracto');
            $table->date('end_contract'); //Fecha de finalización de contracto');
            $table->string('phone'); //Teléfono');
            $table->string('owner_name'); //Nombre del propietario');
            $table->string('phone_two'); //Teléfono');
            $table->date('readjustment')->comment('Reajuste');
            $table->enum('status', ['completed', 'pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readjustments');
    }
};
