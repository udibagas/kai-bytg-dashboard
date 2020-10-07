<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramKerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_kerjas', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tahun');
            $table->smallInteger('bulan');
            $table->unsignedBigInteger('jenis_sarana_id');
            $table->unsignedBigInteger('jenis_pekerjaan_id');
            $table->smallInteger('target');
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
        Schema::dropIfExists('program_kerjas');
    }
}
