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

        "numero_juzgado_id",
        "oficina_interna_id",
        "secretaria_id",
        "juez_id",
        "causa_id_padre",
        "fecha_vinculacion_padre",
        "caratula",
        "estado_causa_id",
        "fecha_estado_causa",
        "fecha_notificado_causa",
        "observacion",
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
    // public function causa()
    // {
    //     return $this->hasOneThrough(
    //         Causa::class,
    //         GrupoActa::class,
    //         'id',
    //         'grupo_acta_id',
    //         'grupo_acta_id',
    //         'id'
    //     );
    // }

    public function padrones()
    {
        return $this->belongsToMany(Padron::class, 'acta_padron')
            ->withPivot('categoria_padron_id')
            ->withTimestamps();
    }

    public function infractores()
    {
        return $this->belongsToMany(Infractor::class, 'acta_infractores')
            ->withPivot('categoria_infractor_id', 'observaciones')
            ->withTimestamps();
    }

    public function infracciones()
    {
        return $this->belongsToMany(Infraccion::class, 'acta_infraccion')
            ->using(ActaInfraccion::class)
            ->withTimestamps();
    }
}
