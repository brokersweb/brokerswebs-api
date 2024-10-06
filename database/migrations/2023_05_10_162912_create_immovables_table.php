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
        Schema::create('immovables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('code')->nullable()->unique(); // Código del inmueble
            $table->string('slug')->nullable();
            $table->string('immonumber')->nullable();
            $table->longText('main_image');
            $table->longText('description')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('rent_price')->nullable();
            $table->string('enrollment')->nullable(); // Número de matrícula
            $table->longText('video_url')->nullable();
            // Tipo de inmueble: casa, apartamento, local, etc
            $table->foreignUuid('immovabletype_id')->constrained('immovabletypes');
            $table->uuid('owner_id'); // Propietario
            $table->enum('category', ['sale', 'rent', 'both'])->default('both');
            $table->uuid('tenant_id')->nullable(); // Inquilino

            // ¿Pertenece a alguna copropiedad?
            $table->enum('co_ownership', ['Si', 'No'])->default('No');
            // Nombre de la copropiedad
            $table->uuid('co_ownership_id')->nullable()->comment('Copropiedad');
            // Estados: active, inactive, sold, rented, under_maintenance, process_sale, process_renting
            $table->string('status')->nullable();
            $table->uuid('building_company_id')->nullable()->constrained('building_companies');
            $table->string('co_adminvalue')->nullable(); //Valor de administración de la copropiedad
            $table->enum('image_status', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->enum('video_status', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->enum('terms', ['true', 'false']); //¿Acepta términos y condiciones?');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immovables');
    }
};
