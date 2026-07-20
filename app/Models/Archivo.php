<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archivo extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'archivos';

    protected $fillable = [
        'path_archivo',
        'nombre_original',
        'extension',
        'size',
        'user_id',
        'archivable_id',
        'archivable_type',
    ];

    /**
     * Obtiene el modelo propietario (polimórfico).
     */
    public function archivable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Obtiene el usuario que subió el archivo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
