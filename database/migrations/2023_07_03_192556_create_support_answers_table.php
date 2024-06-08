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
        Schema::create('support_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('support_id')->constrained('supports');
            $table->foreignUuid('user_id')->constrained('users');
            $table->text('comment');
            $table->longText('evidence')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_answers');
    }
};
