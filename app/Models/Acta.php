<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Acta extends Model
{
    //
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'actas';

    protected $fillable = [
        'grupo_acta_id',
        'numero_acta',
        "year",
        "oficina_id",
        "fecha_labrada",
        "fecha_carga",
        "tipo_id",
        "sub_tipo_id",
        "ley_id",
        "lugar",
        "calle_id",
        "numero_calle",
        "cruce_id",
        "estado_acta_id",
        "fecha_estado",
        "desestimada",
        "fecha_notificado",
        "inspector_1_id",
        "inspector_2_id",
        "numero_juzgado_id",
        "secretaria_id",
        "juez_id",
        "juez_subrogante_id",
        "secretaria_subrogante_id",
        "causa_id_padre",
        "fecha_vinculacion_padre",
        "caratula",
        'color',
        "estado_causa_id",
        "fecha_estado_causa",
        "fecha_notificado_causa",
        "observacion",
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    public function syncInfraccionesConLog(array $infraccionesIds)
    {
        // 1. Hacemos el sync. 
        // Laravel es genial y sync() nos devuelve un array con exactamente qué IDs se agregaron, quitaron o actualizaron.
        $cambios = $this->infracciones()->sync($infraccionesIds);

        // 2. Solo registramos en la auditoría SI REALMENTE HUBO CAMBIOS.
        // Si el usuario le dio a "Guardar" pero no modificó las infracciones, nos ahorramos un log basura.
        if (!empty($cambios['attached']) || !empty($cambios['detached']) || !empty($cambios['updated'])) {

            activity()
                ->causedBy(Auth::user()->id ?? null)
                ->performedOn($this) // $this hace referencia a esta misma Acta
                ->withProperties([
                    'infracciones' => $infraccionesIds,
                    'detalle_cambios'      => $cambios // Guardamos qué se agregó y qué se quitó
                ])
                ->event('syncInfracciones')
                ->log('El usuario actualizó las infracciones del acta');
        }
    }
    public function syncPadronesConLog(array $padronesData)
    {
        // 1. Hacemos el sync con los datos y atributos extra
        $cambios = $this->padrones()->sync($padronesData);

        // 2. Verificamos si realmente hubo alteraciones
        if (!empty($cambios['attached']) || !empty($cambios['detached']) || !empty($cambios['updated'])) {

            activity()
                ->causedBy(Auth::user()->id ?? null)
                ->performedOn($this) // Atamos el log al Acta
                ->withProperties([
                    // Guardamos la estructura completa con los atributos intermedios
                    'padrones_data'   => $padronesData,
                    'detalle_cambios' => $cambios
                ])
                ->event('syncPadrones')
                ->log('El usuario actualizó los padrones vinculados al acta');
        }
    }
    public function syncInfractoresConLog(array $infractoresData)
    {
        // 1. Hacemos el sync con los datos y atributos extra
        $cambios = $this->infractores()->sync($infractoresData);

        // 2. Verificamos si realmente hubo alteraciones
        if (!empty($cambios['attached']) || !empty($cambios['detached']) || !empty($cambios['updated'])) {

            activity()
                ->causedBy(Auth::user()->id ?? null)
                ->performedOn($this) // Atamos el log al Acta
                ->withProperties([
                    // Guardamos la estructura completa con los atributos intermedios
                    'infractores_data'   => $infractoresData,
                    'detalle_cambios' => $cambios
                ])
                ->event('syncInfractores')
                ->log('El usuario actualizó los infractores vinculados al acta');
        }
    }

    public function grupo()
    {
        return $this->belongsTo(GrupoActa::class, 'grupo_acta_id');
    }
    // public function causa()
    // {
    //     return $this->hasOneThrough(
    //         Causa::class,
    //         GrupoActa::class,
    //         'id',
    //         'grupo_acta_id',
    //         'grupo_acta_id',
    //         'id'
    //     );
    // }

    public function padrones()
    {
        return $this->belongsToMany(Padron::class, 'acta_padron')
            ->withPivot('categoria_padron_id')
            ->withTimestamps();
    }

    public function infractores()
    {
        return $this->belongsToMany(Infractor::class, 'acta_infractores')
            ->withPivot('categoria_infractor_id', 'observaciones')
            ->withTimestamps();
    }

    public function infracciones()
    {
        return $this->belongsToMany(Infraccion::class, 'acta_infraccion')
            ->using(ActaInfraccion::class)
            ->withPivot('fecha_infraccion', 'lugar')
            ->withTimestamps();
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'acta_id');
    }

    public function latestMovimiento()
    {
        return $this->hasOne(Movimiento::class, 'acta_id')->latestOfMany();
    }

    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class, 'numero_juzgado_id');
    }
    public function juez()
    {
        return $this->belongsTo(Juez::class, 'juez_id');
    }
    public function juezSubrogante()
    {
        return $this->belongsTo(Juez::class, 'juez_subrogante_id');
    }
    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_id');
    }
    public function secretariaSubrogante()
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_subrogante_id');
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'oficina_id');
    }
}
