<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Infractor extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'infractores';

    protected $fillable = [
        'tipo_id',
        'documento',
        'identificacion',
        'nombre',
        'domicilio'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    public function actas()
    {
        return $this->belongsToMany(Acta::class, 'acta_infractores')
            ->withPivot('categoria_infractor_id', 'observaciones')
            ->withTimestamps();
    }
}
