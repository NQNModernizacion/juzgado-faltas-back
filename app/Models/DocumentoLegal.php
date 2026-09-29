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
        'estado',
        'motivo_anulacion',
        'documento_reemplazado_id',
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

    public function documentoReemplazado()
    {
        return $this->belongsTo(DocumentoLegal::class, 'documento_reemplazado_id');
    }

    public function documentoReemplazante()
    {
        return $this->hasOne(DocumentoLegal::class, 'documento_reemplazado_id');
    }

    public function calcularDesactualizado(): array
    {
        if (!$this->acta_id || !$this->created_at || ($this->estado ?? 'activo') !== 'activo') {
            return [
                'desactualizado' => false,
                'cant_movimientos_posteriores' => 0,
                'cant_estados_posteriores' => 0,
            ];
        }

        $movimientosPosteriores = Movimiento::where('acta_id', $this->acta_id)
            ->where(function ($q) {
                $q->where('created_at', '>', $this->created_at)
                  ->orWhere('fecha_movimiento', '>', $this->created_at);
            })
            ->count();

        $estadosPosteriores = \Illuminate\Support\Facades\DB::table('acta_estado_procesal')
            ->where('acta_id', $this->acta_id)
            ->where('created_at', '>', $this->created_at)
            ->count();

        return [
            'desactualizado' => ($movimientosPosteriores > 0 || $estadosPosteriores > 0),
            'cant_movimientos_posteriores' => $movimientosPosteriores,
            'cant_estados_posteriores' => $estadosPosteriores,
        ];
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
