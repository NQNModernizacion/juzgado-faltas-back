<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspector extends Model
{
    //
    use SoftDeletes;
    protected $table = 'inspectores';

    protected $fillable = [
        "nombre",
        "apellido",
        "habilitado_id",
        "oficina_id",
        "legajo",
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function oficina(): BelongsTo
    {
        // belongsTo(Modelo, 'llave_foranea_en_esta_tabla', 'llave_primaria_en_padre')
        return $this->belongsTo(Oficina::class, 'oficina_id', 'id');
    }

    public function estadoInspector(): BelongsTo
    {
        // belongsTo(Modelo, 'llave_foranea_en_esta_tabla', 'llave_primaria_en_padre')
        return $this->belongsTo(EstadosGenerales::class, 'habilitado_id', 'id');
    }
}
