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
        // Schema::create('categories', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->string('name');
        //     $table->string('type')->nullable()->default('inventary'); // inventary or store
        //     $table->string('subtype')->nullable()->default('material'); // material or tool
        //     $table->timestamps();
        // });

        Schema::table('materials', function (Blueprint $table) {
            // $table->foreignUuid('category_id')->nullable()->constrained('categories');
            $table->string('conditions')->nullable()->default('new'); // new , used
            $table->enum('operative_status', ['active', 'inactive','notfound'])->nullable()->default('active'); // new , used
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->string('conditions')->nullable()->default('new');
            $table->enum('operative_status', ['active', 'inactive','notfound'])->nullable()->default('active'); // new , used
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
