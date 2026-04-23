<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Causa extends Model
{
    use SoftDeletes;

    protected $table = 'causas';

    protected $fillable = [
        'grupo_acta_id',
        'numero',
        'fecha_causa',
        'estado',
        'detalle',
    ];

    public function grupo()
    {
        return $this->belongsTo(GrupoActa::class, 'grupo_acta_id');
    }
}
