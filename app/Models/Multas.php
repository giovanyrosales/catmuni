<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Multas extends Model
{

    protected $table = 'multas';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'codigo',
        'tipo_multa',
        'nombre',

      ];
}
 