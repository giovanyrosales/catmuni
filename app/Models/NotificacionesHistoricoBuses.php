<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionesHistoricoBuses extends Model
{
    protected $table = 'notificaciones_historico_buses';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'id_alertas',
        ];
}
