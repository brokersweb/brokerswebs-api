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
        Schema::create('owners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('lastname');
            $table->enum('document_type', ['CC', 'CE', 'TI', 'PPN', 'NIT', 'RC', 'RUT'])->nullable();
            $table->string('dni')->unique()->nullable();
            $table->string('expedition_place')->nullable();
            $table->string('expedition_date')->nullable();
            $table->string('email');
            $table->string('cellphone')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();
            $table->json('bank_account')->nullable()->comment('Cuenta bancaria');
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro'])->nullable();
            $table->enum('type', ['holder', 'secondary'])->default('secondary');
            $table->string('rut')->nullable(); // Registro Único Tributario
            $table->string('nit')->nullable(); // Número de Identificación Tributaria
            $table->enum('civil_status', ['Soltero', 'Casado', 'Unión libre', 'Viudo', 'Divorciado'])->nullable();
            $table->integer('dependent_people')->comment('N° personas a cargo')->nullable();
            $table->string('profession')->nullable();
            $table->longText('dni_file')->nullable();
            $table->longText('photo')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
