<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class GiroComercial extends Model
{

    protected $table = 'giro_comercial';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nombre_giro'
      ];


}
 