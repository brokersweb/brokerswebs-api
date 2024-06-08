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
        Schema::create('capitalsrentier_type', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('entity'); // Codeudor o Solicitante
            $table->longText('certificate')->comment('Tradición y libertad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capitalsrentier_type');
    }
};
