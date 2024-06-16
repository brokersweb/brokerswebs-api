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
            $table->json('payment_observation')->nullable()->after('terms_payment');
        });

        Schema::table('accounts_collection', function (Blueprint $table) {
            $table->json('payment_observation')->nullable()->after('terms_payment');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_status', function (Blueprint $table) {
            $table->dropColumn('payment_observation');
        });

        Schema::table('accounts_collection', function (Blueprint $table) {
            $table->dropColumn('payment_observation');
        });

    }
};
