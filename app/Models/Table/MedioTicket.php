<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Builder;

class MedioTicket extends Table
{
    protected static function booted()
    {
        $sub_table_name = "medio_ticket";
        static::addGlobalScope($sub_table_name, function (Builder $builder) use ($sub_table_name) {
            $builder->where('name', $sub_table_name);
        });
    }
}
