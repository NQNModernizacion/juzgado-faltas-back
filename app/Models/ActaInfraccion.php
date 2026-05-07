<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActaInfraccion extends Pivot
{
    protected $table = 'acta_infraccion';

    public $incrementing = true;
}
