<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropUnique('antrians_nomor_antrian_unique');
        });
    }

    public function down()
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->unique('nomor_antrian');
        });
    }
};
