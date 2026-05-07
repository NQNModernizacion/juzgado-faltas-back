<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OficinaInterna extends Model
{
    //
    use SoftDeletes;
    protected $table = 'oficina_internas';

    protected $fillable = [];

    protected $hidden = [];
}
