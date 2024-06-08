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
        Schema::create('admincontract_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('immovable_id')->constrained('immovables')->comment('Inmueble');
            $table->longText('owner_dni'); //Cédula del propietario titular');
            $table->longText('certificate'); //Escritura compra venta o acta de entrega de la constructora');
            $table->json('utility_bills')->comment('Servicios públicos');
            $table->longText('invoice'); //Factura de administración');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admincontract_requirements');
    }
};
