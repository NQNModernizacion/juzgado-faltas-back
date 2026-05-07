<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Juzgado extends Model
{
    use SoftDeletes;
    protected $table = 'juzgados';

    protected $fillable = [];

    protected $hidden = [];
}
