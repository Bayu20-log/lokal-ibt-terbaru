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
        Schema::table('berandas', function (Blueprint $table) {
            $table->string('teks1')->nullable()->after('angka1');
            $table->string('teks2')->nullable()->after('angka2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berandas', function (Blueprint $table) {
            $table->dropColumn('teks1');
            $table->dropColumn('teks2');
        });
    }
};