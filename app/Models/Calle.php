<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calle extends Model
{
    //
    use SoftDeletes;
    protected $table = 'calles';

    protected $fillable = [
        "nombre",
        "codigo",
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
