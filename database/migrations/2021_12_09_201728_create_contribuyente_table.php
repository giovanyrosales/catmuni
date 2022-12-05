<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContribuyenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribuyente', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('direccion', 250);
            $table->string('dui', 50);
            $table->string('nit', 50);
            $table->string('registro_comerciante', 50)->nullable();
            $table->string('telefono', 50);
            $table->string('email', 250)->nullable();
            $table->string('fax', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contribuyente');
    }
}
