<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class CalificacionRotulo extends Model
{

    protected $table = 'calificacion_rotulo';
    public $timestamps = true;
    protected $fillable = [
          'id',
          'fecha_calificacion',
          'monto_tarifa',
          'total_impuesto',       
          'id_rotulos',
     
      ];


}