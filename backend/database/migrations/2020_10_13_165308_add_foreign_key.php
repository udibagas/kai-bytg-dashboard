<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sarana_id')->references('id')->on('saranas');
            $table->foreign('jenis_sarana_id')->references('id')->on('jenis_saranas');
            $table->foreign('dipo_id')->references('id')->on('dipos');
            $table->foreign('jalur_id')->references('id')->on('jalurs');
            $table->foreign('jenis_pekerjaan_id')->references('id')->on('jenis_pekerjaans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
