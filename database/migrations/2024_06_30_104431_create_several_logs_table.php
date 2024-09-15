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
        Schema::create('several_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event');
            $table->uuidMorphs('auditable');
            $table->foreignUuid('user_id')->nullable()->constrained();
            $table->text(('description'))->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('several_logs');
    }
};
