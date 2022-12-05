<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class CalificacionBuses extends Model
{

    protected $table = 'calificacion_buses';
    public $timestamps = true;
    protected $fillable = [
          'id',
          'id_contribuyente',
          'fecha_calificacion',  
          'nFicha',       
          'cantidad',
          'monto',
          'pago_mensual',       
          'id_buses_detalle',
          'estado_calificacion',
         
     
      ];


}