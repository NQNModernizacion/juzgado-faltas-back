<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infractor extends Model
{
    use SoftDeletes;
    protected $table = 'infractores';

    protected $fillable = [
        'tipo_id',
        'documento',
        'identificacion',
        'nombre',
        'domicilio'
    ];

    public function actas()
    {
        return $this->belongsToMany(Acta::class, 'acta_infractores')
            ->withPivot('categoria_infractor_id', 'observaciones')
            ->withTimestamps();
    }
}
