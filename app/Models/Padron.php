<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Padron extends Model
{
    //
    use SoftDeletes;
    protected $table = 'padrones';
}
