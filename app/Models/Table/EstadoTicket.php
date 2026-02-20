<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Builder;

class EstadoTicket extends Table
{
    protected $table = 'tables';

    protected static function booted()
    {
        static::addGlobalScope('estado_ticket', function (Builder $builder) {
            $builder->where('name', 'estado_ticket');
        });
    }
}