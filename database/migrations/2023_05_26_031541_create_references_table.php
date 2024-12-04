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
        Schema::create('references', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('lastname');
            $table->date('birthdate')->nullable();
            $table->string('residence_address');
            $table->string('residence_country')->nullable();
            $table->string('residence_department')->nullable();
            $table->string('residence_city')->nullable();
            $table->string('kinship')->comment('Parentesco')->nullable();
            $table->enum('type', ['family', 'personal']);
            $table->string('phone');
            $table->string('email')->nullable()->nullable();
            $table->string('dni')->nullable()->nullable();
            $table->uuidMorphs('referencable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
