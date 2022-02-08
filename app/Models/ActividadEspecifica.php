<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadEspecifica extends Model
{
    protected $table = 'actividad_especifica';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nom_actividad_especifica',
        'id_actividad_economica'
        
        
      ];
}
