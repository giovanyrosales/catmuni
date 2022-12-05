<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class TarifaBus extends Model
{

    protected $table = 'tarifa_bus';
    public $timestamps = true;
    protected $fillable = [
        'id',        
        'monto_tarifa',

      ];
}
 