<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiroEmpresarial extends Model
{
    protected $table = 'giro_empresarial';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nombre_giro_empresarial',
      ];
}
