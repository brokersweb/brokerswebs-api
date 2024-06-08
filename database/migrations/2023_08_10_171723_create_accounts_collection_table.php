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
        Schema::create('accounts_collection', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('immovable_id')->constrained('immovables');
            $table->foreignUuid('tenant_id')->constrained()->comment('Inquilino(titular)');
            $table->string('contract_number')->comment('N° de contrato');
            $table->string('month')->comment('Mes')->nullable();
            $table->string('year')->comment('Año')->nullable();
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_collection');
    }
};
