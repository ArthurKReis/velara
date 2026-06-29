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
        Schema::create('demons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('race');
            $table->integer('level');
            $table->integer('strength');
            $table->integer('magic');
            $table->integer('vitality');
            $table->integer('agility');
            $table->integer('luck');
            $table->string('res_fire')->nullable();
            $table->string('res_ice')->nullable();
            $table->string('res_elec')->nullable();
            $table->string('res_force')->nullable();
            $table->string('res_light')->nullable();
            $table->string('res_dark')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demons');
    }
};
