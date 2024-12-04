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
        Schema::create('cosigners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('lastname');
            $table->enum('document_type', ['CC', 'CE', 'TI', 'PPN', 'NIT', 'RC', 'RUT']);
            $table->string('dni')->unique(); //Número de documento');
            $table->string('expedition_place');
            $table->string('expedition_date');
            $table->string('cellphone');
            $table->string('phone')->nullable();
            // Tipo de trabajo: 1. Empleado 2. Independiente formal 3. Otro = Profesional Independiente 4. Pensionado 5. Rentista de capital
            $table->enum('working_type', ['employee', 'independent', 'freelancerp', 'pensioner', 'capitalrentier'])->nullable()->comment('Tipo de trabajo');
            $table->date('birthdate');
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->enum('civil_status', ['Soltero', 'Casado', 'Unión libre', 'Viudo', 'Divorciado']);
            $table->string('profession');
            $table->string('email');
            $table->string('address');
            $table->string('city_birth');
            $table->string('nationality');
            $table->string('neighborhood');
            $table->string('city_municipality');
            $table->string('department');
            $table->string('country');
            $table->string('professional_title')->nullable();
            $table->string('occupation');
            $table->string('main_economic_activity');
            $table->string('detail_economic_activity');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->integer('has_realestate'); // 0: si y 1: no
            $table->string('property_address')->nullable();
            $table->string('property_city')->nullable();
            $table->integer('has_vehicles'); // 0: si y 1: no
            $table->string('brand')->nullable();
            $table->string('line')->nullable();
            $table->string('model')->nullable();
            $table->integer('has_pledge'); // 0: si y 1: no
            $table->longText('dni_file');

            // Tipo de codeudor
            $table->enum('cosigner_type', ['regular', 'root_property'])
                ->comment('Tipo de codeudor')->default('regular');

            // Documentos sólo para el codeudor con propiedad raiz
            // $table->longText('freedom_tradition')->nullable(); // Certificado de libertad y tradición.
            // $table->longText('lease_contract')->nullable(); // Contrato de arrendamiento.


            // $table->string('phone');
            // $table->longText('dni_file');
            $table->uuidMorphs('cosignerable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cosigners');
    }
};
