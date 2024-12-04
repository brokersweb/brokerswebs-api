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
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('root_number')->unique(); //Número de radicado
            $table->foreignUuid('immovable_id')->constrained('immovables'); //Número de inmueble');
            $table->foreignUuid('applicant_id')->constrained('applicants'); // Solicitante
            $table->enum('status', ['accepted', 'rejected', 'pending', 'inprogress'])->default('pending');
            $table->enum('priority', ['high', 'medium', 'low'])->default('high');
            $table->longText('comment')->nullable();
            $table->text('use_property')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
