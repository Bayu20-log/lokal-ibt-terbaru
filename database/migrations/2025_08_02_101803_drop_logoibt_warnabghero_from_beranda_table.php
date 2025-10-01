<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLogoibtWarnabgheroFromBerandaTable extends Migration
{
    public function up()
    {
        Schema::table('berandas', function (Blueprint $table) {
            $table->dropColumn('logoibt');
            $table->dropColumn('warnabghero');
        });
    }

    public function down()
    {
        Schema::table('berandas', function (Blueprint $table) {
            $table->string('logoibt')->nullable();
            $table->string('warnabghero')->nullable();
        });
    }
}
