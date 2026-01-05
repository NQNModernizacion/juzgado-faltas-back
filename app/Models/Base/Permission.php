<?php

namespace App\Models\Base;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $connection = 'mysql'; // 👈 conexión local real
}
