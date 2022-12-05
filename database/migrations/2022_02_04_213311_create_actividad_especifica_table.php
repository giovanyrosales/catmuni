<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadEspecificaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_especifica', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_actividad_economica')->unsigned();
           
            $table->string('nom_actividad_especifica', 50);

            $table->foreign('id_actividad_economica')->references('id')->on('actividad_economica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividad_especifica');
    }
}
