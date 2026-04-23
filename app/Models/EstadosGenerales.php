<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadosGenerales extends Model
{
    protected $connection;

    protected $table = 'estados_generales';

    protected $fillable = [
        "nombre",
        "value",
        "label",
        "descripcion",
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    const LABEL_TIPO_ACTA = 'tipo_acta';
    const LABEL_SUB_TIPO = 'sub_tipo';
    const LABEL_LEY =  'ley';
    const LABEL_ESTADO = 'estado';
    const LABEL_INFRACCION_GRADO = 'INFRACCION_GRADO';
    const LABEL_CATEGORIA_PADRON = 'CATEGORIA_PADRON';
    const LABEL_TIPO_PADRON = 'TIPO_PADRON';
    const LABEL_CATEGORIA_INFRACTOR = 'CATEGORIA_INFRACTOR';
    const LABEL_DOCUMENTO_TIPO = 'DOCUMENTO_TIPO';

    const HABILITADO = 'HABILITADO';
    const DESHABILITADO = 'DESHABILITADO';

    
    /**
     * Scope para filtrar por uno o varios labels.
     * Acepta un string o un array de strings.
     */
    public function scopePorNombre($query, $nombre)
    {
        // Convertimos a array si viene un solo string para que whereIn siempre funcione
        $nombre = is_array($nombre) ? $nombre : [$nombre];

        return $query->whereIn('nombre', $nombre);
    }
    /**
     * Scope para filtrar por uno o varios labels.
     * Acepta un string o un array de strings.
     */
    public function scopePorLabels($query, $labels)
    {
        // Convertimos a array si viene un solo string para que whereIn siempre funcione
        $labels = is_array($labels) ? $labels : [$labels];

        return $query->whereIn('label', $labels);
    }

    /**
     * Obtiene los inspectores asociados a este estado.
     */
    public function inspectores(): HasMany
    {
        return $this->hasMany(Inspector::class, 'habilitado_id', 'id');
    }
}
