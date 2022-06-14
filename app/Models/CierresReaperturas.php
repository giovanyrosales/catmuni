<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierresReaperturas extends Model
{
    protected $table = 'cierres_reaperturas';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'fecha_a_partir_de',
        'tipo_operacion',
        ];
}
