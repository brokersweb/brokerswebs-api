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
        Schema::create('employments_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('ownerable'); // Codeudor o Solicitante
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address');
            $table->string('neighborhood');
            $table->string('country')->default('Colombia');
            $table->string('department');
            $table->string('email')->nullable();
            $table->string('position');
            $table->string('ext')->nullable();
            $table->string('fax')->nullable();
            $table->string('cellphone');
            $table->string('city');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_information');
    }
};
