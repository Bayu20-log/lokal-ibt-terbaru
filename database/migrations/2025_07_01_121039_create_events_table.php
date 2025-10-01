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
        Schema::create('events', function (Blueprint $table) {
              $table->id();
            $table->text('judulevent')->nullable();
            $table->text('deskripsievent')->nullable();
            $table->string('linkevent')->nullable();
            $table->text('gambarevent')->nullable();
            $table->text('tanggalevent')->nullable();
            $table->text('lokasievent')->nullable();
            $table->text('pukulevent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
