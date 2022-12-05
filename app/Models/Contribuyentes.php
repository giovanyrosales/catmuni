<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Contribuyentes extends Model
{

    protected $table = 'contribuyente';
    public $timestamps = false;
    protected $fillable = [
          'id',
          'nombre',
          'apellido',
          'direccion',
          'dui',
          'nit',
          'registro_comerciante',
          'telefono',
          'email',
          'fax'
      ];


}
 
   