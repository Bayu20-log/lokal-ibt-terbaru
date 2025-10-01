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
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
             $table->string('gambarprofil')->nullable();
            $table->string('judulprofil')->nullable();
            $table->string('subjudulprofil')->nullable();
            $table->text('deskripsiprofil')->nullable();
            $table->string('tujuanprofil1')->nullable();
            $table->string('gambartujuanprofil1')->nullable();
            $table->string('deskripsitujuanprofil1')->nullable();
            $table->string('tujuanprofil2')->nullable();
            $table->string('gambartujuanprofil2')->nullable();
            $table->string('deskripsitujuanprofil2')->nullable();
            $table->string('tujuanprofil3')->nullable();
            $table->string('gambartujuanprofil3')->nullable();
            $table->string('deskripsitujuanprofil3')->nullable();
            $table->string('tujuanprofil4')->nullable();
            $table->string('gambartujuanprofil4')->nullable();
            $table->string('deskripsitujuanprofil4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
