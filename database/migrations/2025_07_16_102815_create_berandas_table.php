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
        Schema::create('berandas', function (Blueprint $table) {
            $table->id();
            $table->string('judulhero')->nullable();
            $table->text('deskripsihero')->nullable();
            $table->string('gambarhero')->nullable();
            $table->string('angka1')->nullable();
            $table->string('angka2')->nullable();
            $table->string('logoibt')->nullable();
            $table->string('warnabghero')->nullable();
            $table->string('linkdaftar1')->nullable();
            $table->string('linkdaftar2')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berandas');
    }
};
