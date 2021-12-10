<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

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
        'inicio_operaciones',
        'direccion', 
        'num_tarjeta', 
        'telefono',
        'id_contribuyente',
        'id_estado_empresa',
        'id_giro_comercial'
      ];


}
 