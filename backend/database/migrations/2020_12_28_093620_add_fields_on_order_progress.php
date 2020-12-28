<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsOnOrderProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_progress', function (Blueprint $table) {
            $table->string('posisi')->nullable();
            $table->json('checklist')->nullable();
            $table->dropColumn(['jenis_detail_pekerjaan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_progress', function (Blueprint $table) {
            $table->dropColumn(['posisi', 'checklist']);
            $table->unsignedBigInteger('jenis_detail_pekerjaan_id')->nullable();
        });
    }
}
