<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Padron extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'tipo_id',
        'identificacion',
        'nombre',
        'data_cache',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'data_cache' => 'array',
        'fecha_actualizacion' => 'datetime',
    ];

    public function tipo()
    {
        return $this->belongsTo(EstadosGenerales::class, 'tipo_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
    protected $table = 'padrones';

    public function actas()
    {
        return $this->belongsToMany(Acta::class, 'acta_padron')
            ->withPivot('categoria_padron_id')
            ->withTimestamps();
    }
}
