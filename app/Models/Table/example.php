<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Builder;

class example extends Table
{
    protected $table = 'tables';

    protected static function booted()
    {
        static::addGlobalScope('estado_example', function (Builder $builder) {
            $builder->where('name', 'estado_example');
        });
    }
}