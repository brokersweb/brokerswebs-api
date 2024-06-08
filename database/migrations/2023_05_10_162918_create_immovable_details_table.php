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
        Schema::create('immovabledetails', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('immovable_id')->constrained('immovables')->onDelete('cascade');
            $table->json('internal_features')->nullable(); //Características internas');
            $table->json('external_features')->nullable(); //Características externas');
            $table->string('common_zones')->nullable();
            $table->integer('bedrooms')->comment('Habitaciones');
            $table->integer('bathrooms')->comment('Baños');
            $table->enum('hasparkings', ['Si', 'No'])->nullable()->comment('¿Tiene Parqueaderos?')->default('No');
            $table->tinyInteger('useful_parking_room')->nullable()->comment('¿Tiene cuarto útil?, 1. Si, 0. No')->default(0);
            $table->float('total_area', 8, 2)->nullable()->comment('Área total');
            $table->float('gross_building_area', 8, 2)->nullable()->comment('Área bruta');
            $table->integer('floor_located')->nullable()->comment('Piso en el que se encuentra');
            $table->integer('stratum')->nullable()->comment('Estrato');
            $table->string('unit_type')->nullable()->comment('unidad');
            $table->foreignUuid('floor_type_id')->nullable()->constrained('floor_types');
            $table->foreignUuid('cuisine_type_id')->nullable()->constrained('cuisine_types');
            $table->string('year_construction')->nullable()->comment('Año de construcción');
            $table->string('tower')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immovabledetails');
    }
};
