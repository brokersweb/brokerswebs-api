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
        Schema::create('comformts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('immovable_id')->constrained('immovables')->onDelete('cascade');
            $table->enum('balcony', ['Si', 'No'])->default('No');
            $table->enum('patio_or_terrace', ['Si', 'No'])->default('No');
            $table->enum('library', ['Si', 'No'])->default('No');
            // Cuarto para los servidores domésticos.
            $table->enum('domestic_server_room', ['Si', 'No'])->default('No');
            $table->enum('alarm', ['Si', 'No'])->default('No');
            $table->enum('airconditioning', ['Si', 'No'])->default('No');
            $table->enum('homeautomation', ['Si', 'No'])->default('No');
            $table->enum('gasnetwork', ['Si', 'No'])->default('No');
            $table->enum('clotheszone', ['Si', 'No'])->default('No');
            $table->enum('waterheater', ['Si', 'No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comformts');
    }
};
