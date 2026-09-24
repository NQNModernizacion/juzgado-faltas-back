<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PlantillaDocumento extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'plantilla_documentos';

    protected $fillable = [
        'codigo',
        'nombre',
        'contenido_base_html',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
