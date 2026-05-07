<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Infraccion extends Model
{
    use SoftDeletes;

    protected $table = 'infracciones';

    protected $fillable = [
        'tipo_infraccion_id',
        'identificacion',
        'descripcion',
        'ley',
        'grado',
        'codigo_caja',
        'valoracion',
        'monto',
        'monto_max',
        'monto_minimo',
        'admite_pago_voluntario',
    ];
    
    protected $casts = [
        'admite_pago_voluntario' => 'boolean',
    ];

    /**
     * Relación con el tipo de infracción (EstadosGenerales).
     */
    public function tipoInfraccion(): BelongsTo
    {
        return $this->belongsTo(EstadosGenerales::class, 'tipo_infraccion_id');
    }

    /**
     * Relación con las actas.
     */
    public function actas(): BelongsToMany
    {
        return $this->belongsToMany(Acta::class, 'acta_infraccion')
            ->using(ActaInfraccion::class)
            ->withTimestamps();
    }
}
