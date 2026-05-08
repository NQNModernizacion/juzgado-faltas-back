<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OficinaInterna extends Model
{
    //
    use SoftDeletes;
    protected $table = 'oficina_internas';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function movimientosOrigen()
    {
        return $this->hasOne(Movimiento::class, 'oficina_id_origen');
    }

    public function movimientosDestino()
    {
        return $this->hasOne(Movimiento::class, 'oficina_id_destino');
    }
}
