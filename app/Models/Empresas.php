<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Empresas extends Model
{

    protected $table = 'empresa';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nombre',
        'matricula_comercio',
        'nit', 
        'tipo_comerciante',
        'referencia_catastral',
        'inicio_operaciones',
        'direccion', 
        'num_tarjeta', 
        'telefono',
        'num_resolucion',
        'excepciones_especificas',
        'id_contribuyente',
        'id_estado_empresa',
        'id_giro_comercial',
        'id_actividad_economica',
      ];


}
 