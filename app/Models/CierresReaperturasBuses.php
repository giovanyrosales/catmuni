<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierresReaperturasBuses extends Model
{
    protected $table = 'cierres_reaperturas_buses';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_buses',        
        'fecha_a_partir_de',
        'tipo_operacion',
        ];
}
