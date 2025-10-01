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
        Schema::create('p2mws', function (Blueprint $table) {
        $table->id();
        $table->string('judulp2mw')->nullable();
        $table->string('subjudulp2mw')->nullable();
        $table->string('deskripsip2mw')->nullable();
        $table->text('linkp2mw')->nullable();

      
        for ($i = 1; $i <= 10; $i++) {
            $table->string("alurp2mw{$i}")->nullable();
            $table->string("deskripsialurp2mw{$i}")->nullable();
        }

       
        for ($i = 1; $i <= 5; $i++) {
            $table->string("namafilep2mw{$i}")->nullable();
            $table->string("deskripsifilep2mw{$i}")->nullable();
            $table->string("filep2mw{$i}")->nullable();
        }
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('p2mws');
    }
};
