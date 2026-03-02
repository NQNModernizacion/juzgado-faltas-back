<?php

use App\Http\Error\ErrorResponse;
use App\Services\ErrorLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

if (!function_exists('sendResponse')) {
    function sendResponse($data, $error = null, $status = 200, $params = null)
    {
        return response()->json([
            'data' => $data,
            'error' => $error,
            'params' => $params,
        ], $status);
    }
}

if (!function_exists('error_response')) {
    function error_response(Throwable $e, ?string $context = null, int $status = 500)
    {
        $reference = ErrorLogService::handle($e, $context);
            
        if ($reference === null) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        }

        // En debug: mostrar mensaje real. En producción: mensaje genérico + referencia.
        $message = config('app.debug')
            ? $e->getMessage()
            : "Ha ocurrido un error interno. Código de referencia: {$reference}";

        return sendResponse(null, [
            //'general'   => $message,
            'reference' => $reference,
        ], $status);
    }
}


if (!function_exists('is_email')) {
    function is_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('clean_html_basic')) {
    /**
     * Limpia una cadena de HTML eliminando etiquetas no permitidas y atributos peligrosos.
     *
     * @param string $html La cadena de HTML a limpiar.
     * @return string La cadena de HTML limpia.
     */
    function clean_html_basic($html)
    {
        // Lista de etiquetas permitidas
        $allowed_tags = '<p><a><b><strong><i><em><ul><ol><li><img></br><br>';

        // Eliminar todas las etiquetas HTML excepto las permitidas
        $clean_html = strip_tags($html, $allowed_tags);

        // Limpiar atributos peligrosos
        $clean_html = preg_replace('/(<[^>]+) on[^>]+/i', '$1', $clean_html);
        return $clean_html;
    }
}

if (!function_exists('saveLog')) {
    function saveLog($e, $event = 'default', $logName = __FUNCTION__): object
    {
        try {
            $log = activity($logName)
                ->causedBy(Auth::id() ?? null)
                ->withProperties($e->getTrace() ?? null)
                ->event($event)
                ->log($e->getMessage());
            // $log = App\Models\BaseModels\Actividad::create([
            //     'log_name' => get_class($e),
            //     'description' => $e->getMessage(),
            //     'subject_type' => get_class($e),
            //     'event' => 'error',
            //     'subject_id' => null,
            //     'causer_type' => Auth::id() ? 'App\Models\User' : null,
            //     'causer_id' => Auth::id() ?? null,
            //     'properties' => $e->getTrace() ? json_encode($e->getTrace()) : null,
            // ]);
            if (env('DISCORD_URL')) {
                $respuesta = ["text" => "`" . env('APP_NAME') . "`" . "\n" . $e->getMessage() . "\n" . get_class($e) . '::' . __FUNCTION__ . "\n" . \Carbon\Carbon::now()->format('d/m/Y H:i:s')];
                Http::post(env('DISCORD_URL'), $respuesta);
            }
            return $log;
        } catch (Throwable $th) {
            return new stdClass;
        }
    }
}

if (!function_exists('log_send_response')) {
    function log_send_response($log)
    {
        if ($log == new stdClass()) {
            $log->id = '1A';
            $log->description = 'Falló el log';
        }
        if (!env('APP_DEBUG')) {
            return sendResponse(null, ErrorResponse::create(['general' => 'Ha ocurrido un error durante la consulta. Código ' . $log->id]), 490);
        }
        return sendResponse(null, ErrorResponse::create(['general' => $log->description]), 490);
    }
}

if (!function_exists('usesSoftDeletes')) {

    function usesSoftDeletes($model)
    {
        return in_array(
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            class_uses($model)
        );
    }
}

if (!function_exists('enviarEmail')) {
    function enviarEmail($body, $subject, $email)
    {
        try {
            if (
                //  true
                app()->environment('production')
            ) {
                $postParams = [
                    'destinationName' => null,
                    'destinationAdresse' => $email,
                    'subject' => $subject,
                    'htmlBody' => $body,
                ];
                $json = (object) Http::withoutVerifying()->post(env('BASE_WEB_LOGIN_API') . 'mail', $postParams)->json();

                if (isset($json->message)) {
                    throw new Exception($json->message);
                }

                if (isset($json->error)) {
                    throw new Exception($json->error);
                }

                return true;
            }

            return false;
        } catch (Exception $e) {
            saveLog(get_class($e), $e->getMessage(), __FUNCTION__, $e->getTrace());

            return false;
        }
    }
}

if (!function_exists('storage_file')) {
    function storage_file($file, string $folder = ''): string
    {
        $path = Storage::disk('serverdata')->put($folder, $file);

        return $path;
    }
}

if (!function_exists('get_file')) {
    function get_file(string $path)
    {
        /* Obtenemos el parth del archivo para luego obtener toda la informacion del archivo */
        $fullPath = Storage::disk('serverdata')->path($path);
        $fileInfo = new File($fullPath);
        $type = $fileInfo->getExtension();
        $type_format = 'image';
        if ($type === 'pdf') {
            $type_format = 'application';
        }

        return [
            'type' => $type,
            'file_name' => $fileInfo->getFilename(),
            'size' => $fileInfo->getSize(),

            /* Enviamos el archivo como base64 */
            'file' => "data:{$type_format}/" . $type . ';base64,' . base64_encode($fileInfo),
        ];
    }
}
