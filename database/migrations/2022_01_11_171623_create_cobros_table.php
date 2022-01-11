<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobros', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_empresa')->unsigned();
            $table->bigInteger('id_usuario')->unsigned();
            
            $table->Integer('monto_pagado', 50);
            $table->string('fecha_pago', 50);
            $table->Timestamps('created_at');

            $table->foreign('id_empresa')->references('id')->on('empresa');
            $table->foreign('id_usuario')->references('id')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cobros');
    }
}
