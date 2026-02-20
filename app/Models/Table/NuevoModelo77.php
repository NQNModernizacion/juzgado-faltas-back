<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Builder;

class NuevoModelo77 extends Table
{
    protected $table = 'tables';

    protected static function booted()
    {
        static::addGlobalScope('nueva_tabla77', function (Builder $builder) {
            $builder->where('name', 'nueva_tabla77');
        });
    }
}