<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Prueba extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'pruebas';

    protected $fillable = [
        'acta_id',
        'tipo_archivo',
        'observacion',
        'user_id',
    ];

    protected $casts = [
        'tipo_archivo' => 'boolean',
    ];

    /**
     * Obtiene el acta al que pertenece la prueba.
     */
    public function acta(): BelongsTo
    {
        return $this->belongsTo(Acta::class, 'acta_id');
    }

    /**
     * Obtiene el usuario que registró la prueba.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el archivo físico asociado a la prueba.
     */
    public function archivo(): MorphOne
    {
        return $this->morphOne(Archivo::class, 'archivable');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
