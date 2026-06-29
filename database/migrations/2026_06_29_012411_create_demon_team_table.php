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
        Schema::create('demon_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('demon_id')->constrained()->onDelete('cascade');
            $table->integer('position')->unsigned();
            $table->timestamps();

            // Garante que um demônio não seja duplicado no mesmo time
            $table->unique(['team_id', 'demon_id']);

            // Garante que uma posição (1 a 5) seja única por time
            $table->unique(['team_id', 'position']);

            // Adiciona uma constraint para limitar a posição entre 1 e 5
            // (opcional: verificação via banco, mas faremos via validação no FormRequest)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demon_team');
    }
};
