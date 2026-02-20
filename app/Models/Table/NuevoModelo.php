<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Builder;

class NuevoModelo extends Table
{
    protected $table = 'tables';

    protected static function booted()
    {
        static::addGlobalScope('nueva_tabla', function (Builder $builder) {
            $builder->where('name', 'nueva_tabla');
        });
    }
}