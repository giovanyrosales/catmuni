<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalificacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calificacion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_detalle_actividad_economica')->unsigned();
            $table->bigInteger('id_empresa')->unsigned();

            $table->string('fecha_calificacion', 50);
            $table->string('tipo_tarifa', 50);
            $table->string('tarifa', 50);
            $table->string('estado_calificacion', 50)->nullable();
            $table->string('licencia', 50)->nullable();
            $table->string('matricula', 50)->nullable();
            $table->string('año_calificacion', 50)->nullable();

            $table->foreign('id_detalle_actividad_economica')->references('id')->on('detalle_actividad_economica');
            $table->foreign('id_empresa')->references('id')->on('empresa');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificacion');
    }
}
