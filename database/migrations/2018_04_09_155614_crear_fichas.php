<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearFichas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_partida')->unsigned();
            $table->enum('color', ['b', 'n']);
            $table->enum('tipo', ['rey', 'reina', 'torre', 'alfil', 'caballo', 'peon']);
            $table->integer('fila')->unsigned();
            $table->integer('columna')->unsigned();
            $table->timestamps();

            $table->index('id_partida');
            $table->index('fila');
            $table->index('columna');

            $table->foreign('id_partida')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichas');
    }
}
