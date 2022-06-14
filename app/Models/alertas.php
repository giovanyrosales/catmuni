<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alertas extends Model
{
    protected $table = 'alertas';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'tipo_alerta',
    ];
}
