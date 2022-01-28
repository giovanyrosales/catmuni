<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class LicenciaMatricula extends Model
{

    protected $table = 'licencia_matricula';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nombre_matricula',
        'monto_matricula',
        'nombre_licencia',
        'monto_licencia',

      ];
}
 