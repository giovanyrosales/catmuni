<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class CalificacionRotuloDetalle extends Model
{

    protected $table = 'calificacion_rotulo_detalle';
    public $timestamps = true;
    protected $fillable = [
          'id',
          'id_rotulos_detalle',
          'id_contribuyente',
          'fecha_calificacion',  
          'nFicha',       
          'cantidad_rotulos',
          'monto',
          'pago_mensual',              
          'estado_calificacion',
         
     
      ];


}