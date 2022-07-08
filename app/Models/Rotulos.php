<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Rotulos extends Model
{

    protected $table = 'rotulos';
    public $timestamps = false;
    protected $fillable = [
          'id',
          'id_empresa',
          'id_estado_rotulo',
          'nom_rotulo',
          'actividad_economica',
          'direccion',
          'fecha_apertura',
          'num_tarjeta',
          'permiso_instalacion',
          'medidas',
          'total_medidas',
          'total_caras',                
          'fecha_cierre'
      ];


}