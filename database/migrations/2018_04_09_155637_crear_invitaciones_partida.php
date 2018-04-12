<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearInvitacionesPartida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('InvitacionesPartidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario1')->unsigned();
            $table->integer('id_usuario2')->unsigned();
            $table->timestamps();

            $table->index('id_usuario1');
            $table->index('id_usuario2');

            $table->foreign('id_usuario1')->references('id')->on('users');
            $table->foreign('id_usuario2')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InvitacionesPartidas');
    }
}
