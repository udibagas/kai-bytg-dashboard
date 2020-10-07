<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->unsignedBigInteger('sarana_id');
            $table->unsignedBigInteger('jenis_sarana_id');
            $table->unsignedBigInteger('dipo_id');
            $table->unsignedBigInteger('jalur_id');
            $table->unsignedBigInteger('jenis_pekerjaan_id');
            $table->smallInteger('prosentase_pekerjaan')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('user_id'); // user yg input data
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
