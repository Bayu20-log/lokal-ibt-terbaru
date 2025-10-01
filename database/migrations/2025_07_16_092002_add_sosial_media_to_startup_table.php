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
            $table->string('igstartup')->nullable()->after('deskripsistartup');
            $table->string('fbstartup')->nullable()->after('igstartup');
            $table->string('xstartup')->nullable()->after('fbstartup');
            $table->string('linkedinstartup')->nullable()->after('xstartup');
        });
    }

    public function down()
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->dropColumn(['igstartup', 'fbstartup', 'xstartup', 'linkedinstartup']);
        });
    }
};
