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
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_service_id')->constrained('service_orders');
            $table->enum('status', ['progress', 'confirmed', 'cancelled']);
            $table->foreignUuid('created_by')->constrained('users');
            // %  IVA Servicios
            $table->integer('service_vat');
            $table->decimal('total_service', 10, 2);
            // %  IVA Materiales
            $table->integer('material_vat');
            $table->decimal('total_material', 10, 2);
            // % IVA General
            $table->string('tax_name')->nullable();
            $table->decimal('tax_value', 12, 2)->nullable();

            /// ---------- Cargos Adicionales ----------------------
            // Aseo General
            $table->decimal('general_housekeeping', 12, 2)->nullable();
            // Mano de Obra
            $table->decimal('labor', 12, 2)->nullable();
            // Gastos Operativos
            $table->decimal('operating_expenses', 12, 2)->nullable();
            // Ayudantes Extras
            $table->decimal('extra_helpers', 12, 2)->nullable();
            // Transporte
            $table->decimal('transportation', 12, 2)->nullable();

            // Descuento General
            $table->integer('discount')->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);

            $table->decimal('total', 12, 2); // Subtotal + IVA - Descuento
            $table->decimal('subtotal', 12, 2); // Antes de IVA

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
