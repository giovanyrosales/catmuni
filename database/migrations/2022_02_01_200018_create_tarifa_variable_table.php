<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateTarifaVariableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifa_variable', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_actividad_economica')->unsigned();
            
            $table->string('limite_inferior', 50);
            $table->string('limite_superior', 50)->Nullable();
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
        Schema::dropIfExists('tarifa_variable');
    }
}
