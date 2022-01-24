<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleActividadEconomicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_actividad_economica', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('id_actividad_economica')->unsigned();
            
            $table->string('limite_inferior', 50);
            $table->string('fijo', 50);
            $table->string('categoria', 50);
            $table->string('millar', 50);
            $table->string('excedente', 50);

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
        Schema::dropIfExists('detalle_actividad_economica');
    }
}
