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
        Schema::create('financial_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('ownerable'); // Codeudor o Solicitante
            $table->double('income', 12, 2);
            $table->double('total_expenses', 12, 2);
            $table->double('other_income', 12, 2);
            $table->double('total_assets', 12, 2);
            $table->double('which', 12, 2)->nullable()->default(0);
            $table->double('total_liabilities', 12, 2);
            $table->double('total_income', 12, 2);
            $table->double('is_declarant', 12, 2);
            $table->double('withholding_agent', 12, 2);
            $table->double('vat_agent', 12, 2);
            $table->double('taxpayer', 12, 2);
            $table->double('chamber_commerce', 12, 2);
            $table->double('has_bankaccounts', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_information');
    }
};
