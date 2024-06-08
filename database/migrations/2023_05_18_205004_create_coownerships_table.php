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
        Schema::create('coownerships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('nit')->unique()->nullable(); //Número de Identificación Tributaria
            $table->string('phone')->nullable()->unique(); //Teléfono fijo
            $table->string('cellphone')->nullable()->unique(); //Teléfono celular
            $table->string('email')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coownerships');
    }
};
