<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifaFijaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifa_fija', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_actividad_economica')->unsigned();
           
            $table->string('nombre_actividad', 50);
            $table->string('limite_inferior', 50)->nullable();
            $table->string('limite_superior', 50)->nullable();  
            $table->string('impuesto_mensual', 50);

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
        Schema::dropIfExists('tarifa_fija');
    }
}
