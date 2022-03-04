<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRotulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rotulos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_contribuyente')->unsigned();
            $table->bigInteger('id_empresa')->unsigned();
           
            $table->string('nom_rotulo', 200);
            $table->string('actividad_economica', 50);
            $table->string('direccion', 200);
            $table->string('fecha_apertura', 50);
            $table->string('num_tarjeta', 50);
            $table->string('permiso_instalacion', 50);
            $table->string('medidas', 200);
            $table->string('estado', 50);

            $table->foreign('id_contribuyente')->references('id')->on('contribuyente');
            $table->foreign('id_empresa')
            ->nullable()
            ->references('id')->on('empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rotulos');
    }
}
