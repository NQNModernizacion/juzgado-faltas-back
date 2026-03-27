<?php

namespace App\Http\Controllers\Strategies\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthStrategy
{
    public function authenticate(Request $request): User;
}
