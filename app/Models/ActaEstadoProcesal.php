<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActaEstadoProcesal extends Pivot
{
    protected $table = 'acta_estado_procesal';

    public $incrementing = true;

    protected $fillable = [
        'acta_id',
        'estado_procesal_id',
        'fecha',
        'observacion',
        'infractor_id',
        'imputado_datos',
        'user_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * User who made the transition.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Associated infractor (imputado), if any.
     */
    public function infractor()
    {
        return $this->belongsTo(Infractor::class, 'infractor_id');
    }

    /**
     * Associated Acta.
     */
    public function acta()
    {
        return $this->belongsTo(Acta::class, 'acta_id');
    }

    /**
     * Associated EstadoProcesal.
     */
    public function estadoProcesal()
    {
        return $this->belongsTo(EstadoProcesal::class, 'estado_procesal_id');
    }
}
