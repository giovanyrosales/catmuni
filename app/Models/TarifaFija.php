<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class TarifaFija extends Model
{

    protected $table = 'tarifa_fija';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_giro_empresarial',
        'id_actividad_especifica',
        'codigo',
        'limite_inferior',
        'limite_superior',
        'impuesto_mensual',

      ];
}
 