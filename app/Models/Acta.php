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
        'numero_acta',
        "year",
        "oficina_id",
        "fecha_labrada",
        "fecha_carga",
        "tipo_id",
        "sub_tipo_id",
        "ley_id",
        "lugar",
        "calle_id",
        "numero_calle",
        "cruce_id",
        "estado_acta_id",
        "fecha_estado",
        "desestimada",
        "fecha_notificado",
        "inspector_1_id",
        "inspector_2_id",
        "infracciones"
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
