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
        Schema::table('account_status', function (Blueprint $table) {
            $table->date('expiration_date')->nullable()->after('year');
            $table->double('amount')->nullable()->after('expiration_date');
            // Valor IVA
            $table->double('amount_vat')->nullable()->after('amount');
            // Valor retefuente
            $table->double('amount_retention')->nullable()->after('amount_vat');
            // Items total
            $table->double('items')->nullable()->after('amount_retention');
            $table->string('amount_in_letters')->nullable()->after('items');
            $table->string('terms_payment')->nullable()->after('amount_in_letters');
            $table->text('observation')->nullable()->after('terms_payment');
        });

        Schema::table('accounts_collection', function (Blueprint $table) {
            $table->date('expiration_date')->nullable()->after('year');
            $table->double('amount')->nullable()->after('expiration_date');
            // Valor IVA
            $table->double('amount_vat')->nullable()->after('amount');
            // Valor retefuente
            $table->double('amount_retention')->nullable()->after('amount_vat');
            // Items total
            $table->double('items')->nullable()->after('amount_retention');
            $table->string('amount_in_letters')->nullable()->after('items');
            $table->string('terms_payment')->nullable()->after('amount_in_letters');
            $table->text('observation')->nullable()->after('terms_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_status', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
            $table->dropColumn('amount');
            $table->dropColumn('amount_vat');
            $table->dropColumn('amount_retention');
            $table->dropColumn('items');
            $table->dropColumn('amount_in_letters');
            $table->dropColumn('terms_payment');
        });

        Schema::table('accounts_collection', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
            $table->dropColumn('amount');
            $table->dropColumn('amount_vat');
            $table->dropColumn('amount_retention');
            $table->dropColumn('items');
            $table->dropColumn('amount_in_letters');
            $table->dropColumn('terms_payment');
        });
    }
};
