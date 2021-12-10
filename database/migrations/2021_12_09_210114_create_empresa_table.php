<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('id_contribuyente')->unsigned();
            $table->bigInteger('id_estado_empresa')->unsigned();
            $table->bigInteger('id_giro_comercial')->unsigned();   


            $table->string('nombre', 50);
            $table->string('matricula_comercio', 50)->nullable();
            $table->string('nit', 50)->nullable();
            $table->string('tipo_comerciante', 50)->nullable();
            $table->string('inicio_operaciones', 250);
            $table->string('direccion', 50);
            $table->string('num_tarjeta', 50);
            $table->string('telefono', 50); 

            $table->foreign('id_contribuyente')->references('id')->on('contribuyente');
            $table->foreign('id_estado_empresa')->references('id')->on('estado_empresa');
            $table->foreign('id_giro_comercial')->references('id')->on('giro_comercial');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}
