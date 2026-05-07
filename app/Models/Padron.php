<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Padron extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'tipo_id',
        'identificacion',
        'nombre'
    ];
    protected $table = 'padrones';

    public function actas()
    {
        return $this->belongsToMany(Acta::class, 'acta_padron')
            ->withPivot('categoria_padron_id')
            ->withTimestamps();
    }
}
