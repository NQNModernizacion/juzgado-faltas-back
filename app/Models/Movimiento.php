<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Movimiento extends Model
{
    use LogsActivity;
    use SoftDeletes;


    protected $table = 'movimientos';

    protected $fillable = [
        'acta_id',
        'oficina_id_origen',
        'oficina_id_destino',
        'motivo',
        'fecha_movimiento',
        'fojas',
        'fecha_vecimiento'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function acta()
    {
        return $this->belongsTo(Acta::class, 'acta_id');
    }

    public function oficinaOrigen()
    {
        return $this->belongsTo(OficinaInterna::class, 'oficina_id_origen');
    }

    public function oficinaDestino()
    {
        return $this->belongsTo(OficinaInterna::class, 'oficina_id_destino');
    }
}
