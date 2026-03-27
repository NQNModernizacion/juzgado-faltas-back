<?php

namespace App\Models;

use App\Utils\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonasAdmin extends Model
{
    //
    use ReadOnlyModel;
    protected $connection = 'admin';
    protected $table = 'Personas';

    public function UsuarioAdmin(): BelongsTo
    {
        return $this->belongsTo(UsuariosAdmin::class, 'id', 'PersonaID');
    }
}
