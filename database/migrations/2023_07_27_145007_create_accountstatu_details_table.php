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
        Schema::create('accountstatu_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('accountstatus_id')->constrained('account_status');
            $table->integer('qty');
            $table->string('concept')->comment('Concepto');
            $table->decimal('value_unit', 10, 2);
            $table->decimal('amount', 10, 2)->comment('Monto o Total');
            $table->string('immovable_code');
            $table->string('owner_dni');
            $table->string('cutoff_date')->comment('Fecha de corte');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountstatus_details');
    }
};
