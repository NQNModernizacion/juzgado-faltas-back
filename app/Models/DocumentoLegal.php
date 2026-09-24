<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DocumentoLegal extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'documento_legal';

    protected $fillable = [
        'plantilla_documento_id',
        'causa_id',
        'acta_id',
        'tipo',
        'contenido_html',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function plantilla()
    {
        return $this->belongsTo(PlantillaDocumento::class, 'plantilla_documento_id');
    }

    public function causa()
    {
        return $this->belongsTo(Acta::class, 'acta_id');
    }

    public function acta()
    {
        return $this->belongsTo(Acta::class, 'acta_id');
    }

    public function getCausaIdAttribute()
    {
        return $this->acta_id;
    }

    public function setCausaIdAttribute($value)
    {
        $this->attributes['acta_id'] = $value;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
