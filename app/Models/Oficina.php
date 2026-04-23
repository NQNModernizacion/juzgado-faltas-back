<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oficina extends Model
{
    //
    use SoftDeletes;
    protected $table = 'oficinas';

    protected $fillable = [
        "codigo",
        "descripcion",
        "codigo_caja",
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * Get the comments for the blog post.
     */
    public function inspectores(): HasMany
    {
        return $this->hasMany(Inspector::class, 'oficina_id', 'id');
    }
}
