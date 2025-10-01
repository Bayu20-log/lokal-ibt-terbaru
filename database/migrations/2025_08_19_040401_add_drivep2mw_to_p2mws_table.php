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
    Schema::table('p2mws', function (Blueprint $table) {
        for ($i = 1; $i <= 5; $i++) {
            $table->string("drivep2mw{$i}")->nullable()->after("filep2mw{$i}");
        }
    });
}

public function down(): void
{
    Schema::table('p2mws', function (Blueprint $table) {
        for ($i = 1; $i <= 5; $i++) {
            $table->dropColumn("drivep2mw{$i}");
        }
    });
}

};
