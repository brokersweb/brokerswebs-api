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
            $table->date('expiration_date')->nullable();
            $table->double('amount')->nullable();
            // Valor IVA
            $table->double('amount_vat')->nullable();
            // Valor retefuente
            $table->double('amount_retention')->nullable();
            // Items total
            $table->double('items')->nullable();
            $table->string('amount_in_letters')->nullable();
            $table->string('terms_payment')->nullable();
            $table->json('payment_observation')->nullable();
            $table->text('observation')->nullable();
            $table->double('amount_paid')->nullable();
            $table->enum('status', ['created', 'sent', 'pending_payment', 'paid', 'partially_paid', 'overdue', 'cancelled', 'amended'])->default('created');
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
