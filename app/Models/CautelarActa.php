<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class CautelarActa extends Pivot
{
    use SoftDeletes;

    protected $table = 'cautelar_acta';

    protected $fillable = [
        'acta_id',
        'cautelar_acta'
    ];
}
