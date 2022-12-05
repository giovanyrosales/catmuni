<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierresReaperturasRotulo extends Model
{
    protected $table = 'cierre_reapertura_rotulo';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_rotulos',     
        'fecha_a_partir_de',
        'tipo_operacion',
        ];
}
