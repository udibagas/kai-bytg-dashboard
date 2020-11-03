<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrutanOnJenisDetailPekerjaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenis_detail_pekerjaans', function (Blueprint $table) {
            $table->tinyInteger('urutan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jenis_detail_pekerjaans', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
}
