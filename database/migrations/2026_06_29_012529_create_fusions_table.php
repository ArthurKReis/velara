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
        Schema::create('fusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demon_a_id')->constrained('demons')->onDelete('cascade');
            $table->foreignId('demon_b_id')->constrained('demons')->onDelete('cascade');
            $table->foreignId('demon_result_id')->constrained('demons')->onDelete('cascade');
            $table->timestamps();

            // Evita duplicação de receitas de fusão
            $table->unique(['demon_a_id', 'demon_b_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fusions');
    }
};
