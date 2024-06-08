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
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->enum('document_type', ['CC', 'CE', 'TI', 'PPN', 'NIT', 'RC', 'RUT']);
            $table->string('dni')->unique();
            $table->string('expedition_place');
            $table->string('expedition_date');
            $table->string('email')->nullable();
            $table->string('cellphone')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();
            $table->json('bank_account')->nullable()->comment('Cuenta bancaria');
            $table->date('birthdate');
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->enum('type', ['holder', 'secondary'])->default('secondary');
            $table->enum('civil_status', ['Soltero', 'Casado', 'Unión libre', 'Viudo', 'Divorciado']);
            $table->integer('dependent_people')->comment('N° de personas a cargo')->nullable();
            $table->string('profession')->nullable();
            $table->longText('dni_file')->comment('Archivo de documento de identificación')->nullable();
            $table->longText('photo')->nullable();
            $table->enum('working_type', ['employee', 'independent', 'freelancerp', 'pensioner', 'capitalrentier'])->nullable()->comment('Tipo de trabajo');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
