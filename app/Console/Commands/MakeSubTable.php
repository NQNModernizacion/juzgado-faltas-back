<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeSubTable extends Command
{
    // Opciones del comando
    protected $signature = 'make:sub-table 
                            {--model= : Nombre del modelo (ej: EstadoTicket)}
                            {--name= : Nombre del subconjunto en la tabla (ej: estado_ticket)}
                            {--labels= : Lista de labels separados por coma (ej: "Abierto,Resuelto")}
                            {--file= : Ruta a un archivo txt con un label por línea}';

    protected $description = 'Crea un modelo hijo de Table e inserta los datos en la DB.';

    public function handle()
    {
        $modelName = $this->option('model');
        $name = $this->option('name');
        $labelsRaw = $this->option('labels');
        $file = $this->option('file');

        if (!$modelName || !$name) {
            $this->error('Faltan parámetros: --model y --name son obligatorios.');
            return;
        }

        if (!$labelsRaw && !$file) {
            $this->error('Debes usar --labels o --file para indicar los datos.');
            return;
        }

        // 1. Obtener los datos a insertar
        $labels = [];
        if ($file) {
            if (!File::exists($file)) return $this->error("El archivo no existe.");
            $labels = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        } else {
            $cleanLabels = str_replace(["[", "]", "'", "\""], "", $labelsRaw);
            $labels = array_map('trim', explode(',', $cleanLabels));
        }

        // 2. Insertar en la Base de Datos
        $insertData = [];
        foreach ($labels as $key => $value) {
            $snakeCaseValue = Str::snake(preg_replace('/[^a-z0-9_]/', '', Str::ascii(strtolower($value))));
            $insertData[] = [
                'name' => $name,
                'value' => $snakeCaseValue,
                'label' => $value,
                'descripcion' => $value,
            ];
        }
        DB::table('tables')->insert($insertData);
        $this->info("Registros insertados en la base de datos.");

        // 3. Crear el archivo del Modelo automáticamente
        $this->createModel($modelName, $name);
    }

    private function createModel($modelName, $name)
    {
        // Ruta donde se creará el archivo basándonos en tu directorio
        $path = app_path("Models/Table/{$modelName}.php");

        if (File::exists($path)) {
            $this->warn("El modelo {$modelName}.php ya existe.");
            return;
        }

        // El contenido exacto que tendrá el nuevo archivo PHP
        $stub = <<<EOT
<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Builder;

class {$modelName} extends Table
{
    protected \$table = 'tables';

    protected static function booted()
    {
        static::addGlobalScope('{$name}', function (Builder \$builder) {
            \$builder->where('name', '{$name}');
        });
    }
}
EOT;

        File::put($path, $stub);
        $this->info("Archivo creado exitosamente en: {$path}");
    }
}