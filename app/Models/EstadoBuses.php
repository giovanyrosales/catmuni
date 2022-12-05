<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class EstadoBuses extends Model
{

    protected $table = 'estado_buses';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'estado'

      ];


}
 