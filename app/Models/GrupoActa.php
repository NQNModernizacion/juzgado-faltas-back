<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GrupoActa extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'grupos_actas';

    protected $fillable = [
        'estado',
        'observacion',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    public function actas()
    {
        return $this->hasMany(Acta::class, 'grupo_acta_id');
    }

    public function causa()
    {
        return $this->hasOne(Causa::class, 'grupo_acta_id');
    }

    public function tieneCausa(): bool
    {
        return $this->causa()->exists();
    }

    public function esIndividual(): bool
    {
        return $this->actas()->count() === 1;
    }
}
