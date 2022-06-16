<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierresReaperturasRotulos extends Model
{
    protected $table = 'cierres_reaperturas_rotulo';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_rotulos',
        'id_empresa',
        'fecha_a_partir_de',
        'tipo_operacion',
        ];
}
