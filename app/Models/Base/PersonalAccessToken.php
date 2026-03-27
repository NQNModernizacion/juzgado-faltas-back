<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;


class PersonalAccessToken extends SanctumPersonalAccessToken
{
    //
    protected $connection = 'mysql'; // o el nombre real de tu conexión local (ej: 'default')

}
