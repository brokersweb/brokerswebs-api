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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('xml')->nullable();
            $table->longText('doc_file');
            $table->string('doc_name')->nullable();
            $table->uuidMorphs('entityable');
            $table->string('type')->nullable();
            // Consecutivo
            $table->string('sequential')->nullable();
            $table->foreignUuid('user_id')->constrained();

            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
