<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRobotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->integer('x_pos');
            $table->integer('y_pos');
            $table->char('heading',1);
            $table->string('commands');
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
        Schema::dropIfExists('robot');
    }
}
