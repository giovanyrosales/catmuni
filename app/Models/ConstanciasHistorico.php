<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstanciasHistorico extends Model
{
    protected $table = 'constancias_historico';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'tipo_constancia',
        'num_resolucion'
        ];
}
