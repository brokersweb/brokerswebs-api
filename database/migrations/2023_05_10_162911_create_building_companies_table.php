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
        Schema::create('building_companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            // Número de Identificación Tributaria
            $table->string('nit')->unique()->nullable();
            // Teléfono fijo
            $table->string('phone')->nullable()->unique();
            // Teléfono celular
            $table->string('cellphone')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            // URL del sitio web
            $table->longText('url_website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_companies');
    }
};
