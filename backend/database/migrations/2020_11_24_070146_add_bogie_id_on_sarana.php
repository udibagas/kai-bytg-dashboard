<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBogieIdOnSarana extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saranas', function (Blueprint $table) {
            $table->unsignedBigInteger('bogie_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saranas', function (Blueprint $table) {
            $table->dropColumn(['bogie_id']);
        });
    }
}
