<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EstadoProcesal extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'estados_procesales';

    protected $fillable = [
        'estado',
        'descripcion',
        'tipo',
        'es_antec',
        'antec_vig_dias',
        'porc_bonif',
        'bonif_vig_dias',
        'gen_notif',
        'notif_cant_dias',
        'form_autom',
        'perm_pago',
        'perm_plan',
        'perm_vol',
        'desestima',
        'tipo_causa',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function actas()
    {
        return $this->belongsToMany(Acta::class, 'acta_estado_procesal')
            ->using(ActaEstadoProcesal::class)
            ->withPivot('id', 'fecha', 'observacion', 'infractor_id', 'imputado_datos', 'user_id')
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
