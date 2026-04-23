<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acta extends Model
{
    //
    use SoftDeletes;
    protected $table = 'actas';

    protected $fillable = [
        'grupo_acta_id',
        "codigo",
        "nombre",
        "descripcion",
        "fecha",
        "tipo_acta",
        "sub_tipo",
        "ley",
        "estado_general",
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function grupo()
    {
        return $this->belongsTo(GrupoActa::class, 'grupo_acta_id');
    }
    public function causa()
    {
        return $this->hasOneThrough(
            Causa::class,
            GrupoActa::class,
            'id',
            'grupo_acta_id',
            'grupo_acta_id',
            'id'
        );
    }
}
