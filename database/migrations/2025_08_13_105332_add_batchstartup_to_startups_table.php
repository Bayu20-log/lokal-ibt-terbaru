<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('startups', function (Blueprint $table) {
        $table->string('batchstartup')->nullable()->after('jenisstartup');
    });
}

public function down()
{
    Schema::table('startups', function (Blueprint $table) {
        $table->dropColumn('batchstartup');
    });
}

};
