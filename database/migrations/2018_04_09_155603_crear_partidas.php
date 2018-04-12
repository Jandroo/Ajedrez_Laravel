<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearPartidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_negro')->unsigned();
            $table->integer('id_blanco')->unsigned();
            $table->enum('turno', ['b', 'n'])->default("b");
            $table->timestamps();

            $table->index('id_negro');
            $table->index('id_blanco');
            $table->index('turno');
            
            $table->foreign('id_negro')->references('id')->on('users');
            $table->foreign('id_blanco')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidas');
    }
}
