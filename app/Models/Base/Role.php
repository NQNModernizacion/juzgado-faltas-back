<?php

namespace App\Models\Base;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'mysql'; // 👈 conexión local real
}
