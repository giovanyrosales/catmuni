<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class InspeccionRotulos extends Model
{

    protected $table = 'inspeccion_rotulos';
    public $timestamps = false;
    protected $fillable = [
          'id',
          'hora_inspeccion',
          'fecha_inspeccion',
          'coordenadas',
          'imagen',
          'id_rotulos',
      ];


}