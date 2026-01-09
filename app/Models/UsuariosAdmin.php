<?php

namespace App\Models;

use App\Utils\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuariosAdmin extends Model
{
    //
    use ReadOnlyModel;
    protected $connection = 'admin';
    protected $table = 'Usuarios';

    protected $primaryKey = 'ReferenciaID';

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ReferenciaID', 'id');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(PersonasAdmin::class, 'PersonaID', 'id');
    }
}
