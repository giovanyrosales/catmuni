<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionesHistoricoRotulos extends Model
{
    protected $table = 'notificaciones_historico_rotulos';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'id_alertas',
        ];
}
