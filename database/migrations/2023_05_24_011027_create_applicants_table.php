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
        Schema::create('applicants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('lastname');
            $table->enum('document_type', ['CC', 'CE', 'TI', 'PPN', 'NIT', 'RC', 'RUT']);
            $table->string('dni')->unique(); //Número de documento');
            $table->string('expedition_place');
            $table->string('expedition_date');
            $table->string('phone');
            // Tipo de trabajo: 1. Empleado 2. Independiente formal 3. Otro = Profesional Independiente 4. Pensionado 5. Rentista de capital
            $table->enum('working_type', ['employee', 'independent', 'freelancerp', 'pensioner', 'capitalrentier'])->comment('Tipo de trabajo');
            $table->date('birthdate');
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->enum('civil_status', ['Soltero', 'Casado', 'Unión libre', 'Viudo', 'Divorciado']);
            $table->integer('dependent_people'); //Número de personas a cargo');
            $table->string('profession');
            $table->string('email');
            $table->string('address');
            $table->longText('dni_file'); //Archivo de documento de identificación');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
