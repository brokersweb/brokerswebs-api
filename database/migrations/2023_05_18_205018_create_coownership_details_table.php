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
        Schema::create('coownership_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('coownership_id')->constrained('coownerships')->comment('Copropiedad');
            $table->enum('elevator', ['Si', 'No'])->default('No')->comment('Ascensor');
            $table->enum('intercom', ['Si', 'No'])->default('No')->comment('Citófono');
            $table->enum('garbage_shut', ['Si', 'No'])->default('No')->comment('Chute de basura');
            $table->enum('visitor_parking', ['Si', 'No'])->default('No')->comment('Parqueadero de visitantes');
            $table->enum('social_room', ['Si', 'No'])->default('No')->comment('Salón social');
            $table->enum('sports_court', ['Si', 'No'])->default('No')->comment('Cancha deportiva');
            $table->enum('bbq_area', ['Si', 'No'])->default('No')->comment('Zona BBQ');
            $table->enum('childish_games', ['Si', 'No'])->default('No')->comment('Juegos infantiles');
            $table->enum('parkland', ['Si', 'No'])->default('No')->comment('Zonas verdes');
            $table->enum('jogging_track', ['Si', 'No'])->default('No')->comment('Pista de trote o senderos');
            $table->enum('jacuzzi', ['Si', 'No'])->default('No')->comment('Jacuzzi');
            $table->enum('turkish', ['Si', 'No'])->default('No')->comment('Turco');
            $table->enum('gym', ['Si', 'No'])->default('No')->comment('Gimnasio');
            $table->enum('closed_circuit_tv', ['Si', 'No'])->default('No')->comment('Circuito cerrado de TV');
            $table->enum('climatized_pool', ['Si', 'No'])->default('No')->comment('Piscina climatizada');
            $table->enum('goal', ['Si', 'No'])->default('No')->comment('Portería');
            $table->string('goal_hours')->nullable()->comment('Horario de portería');
            $table->enum('petfriendly_zone', ['Si', 'No'])->default('No')->comment('Zona pet friendly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coownership_details');
    }
};
