<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dispensa extends Model
{
    
    protected $table = 'dispensa';
    public $timestamps = false;
    protected $fillable = [
          'id',
          'fecha_inicio_periodo',
          'fecha_fin_periodo',
          'estado',
    ];
}
