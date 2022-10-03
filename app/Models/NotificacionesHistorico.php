<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionesHistorico extends Model
{
    protected $table = 'notificaciones_historico';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_alertas',
        ];
}
