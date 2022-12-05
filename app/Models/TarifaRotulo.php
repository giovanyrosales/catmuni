<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class TarifaRotulo extends Model
{

    protected $table = 'tarifa_rotulo';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'limite_inferior',
        'limite_superior',
        'monto_tarifa',

      ];
}
 