<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class CalificacionBus extends Model
{

    protected $table = 'calificacion_bus';
    public $timestamps = true;
    protected $fillable = [
          'id',
          'id_buses',
          'id_empresa',
          'fecha_calificacion',
          'tarifa_mensual',
          'tarifa_total',
          'estado_calificacion'
     
      ];


}