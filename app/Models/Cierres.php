<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cierres extends Model
{
    protected $table = 'cierres';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'fecha_a_partir_de',
        ];
}
