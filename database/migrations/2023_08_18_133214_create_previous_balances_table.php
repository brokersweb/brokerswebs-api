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
        Schema::create('previous_balances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->foreignUuid('account_status_id')->constrained('account_status')->onDelete('cascade');
            $table->uuidMorphs('accountable');
            $table->decimal('balance', 12, 2)->comment('Saldo Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_balances');
    }
};
