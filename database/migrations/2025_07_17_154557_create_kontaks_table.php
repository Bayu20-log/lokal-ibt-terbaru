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
        Schema::create('kontaks', function (Blueprint $table) {
            $table->id();
             $table->string('alamatfooter')->nullable();
            $table->string('emailfooter')->nullable();
            $table->string('namausefullinks1')->nullable();
            $table->string('namausefullinks2')->nullable();
            $table->string('namausefullinks3')->nullable();
            $table->string('usefullinks1')->nullable();
            $table->string('usefullinks2')->nullable();
            $table->string('usefullinks3')->nullable();
            $table->string('xfooter')->nullable();
            $table->string('igfooter')->nullable();
            $table->string('fbfooter')->nullable();
             $table->string('ytfooter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontaks');
    }
};
