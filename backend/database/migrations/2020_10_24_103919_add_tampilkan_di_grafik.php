<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTampilkanDiGrafik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenis_pekerjaans', function (Blueprint $table) {
            $table->boolean('tampilkan_di_grafik')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jenis_pekerjaans', function (Blueprint $table) {
            $table->dropColumn('tampilkan_di_grafik');
        });
    }
}
