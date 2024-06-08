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
            $table->string('dni');
            $table->date('birthdate');
            $table->string('expedition_country');
            $table->string('expedition_department');
            $table->string('expedition_city');
            $table->string('expedition_date');

            // Tipo de trabajo: 1. Empleado 2. Independiente formal 3. freelancerp = Profesional Independiente
            // 4. Pensionado 5. Rentista de capital.
            // Esto no aplica para codeudor con propiedad raiz
            $table->enum('working_type', ['employee', 'independent', 'freelancerp', 'pensioner', 'capitalrentier'])
                ->comment('Tipo de trabajo')->nullable();

            // Tipo de codeudor
            $table->enum('cosigner_type', ['regular', 'root_property'])
                ->comment('Tipo de codeudor')->default('regular');


            // Documentos sólo para el codeudor con propiedad raiz
            $table->longText('freedom_tradition')->nullable(); // Certificado de libertad y tradición.
            $table->longText('lease_contract')->nullable(); // Contrato de arrendamiento.


            $table->string('phone');
            $table->longText('dni_file');
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
