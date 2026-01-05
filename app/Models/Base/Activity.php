<?php

namespace App\Models\Audit;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    protected $connection = 'mysql';
}
