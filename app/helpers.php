<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;

use App\Services\ErrorLogService; 

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

//las savelog y log_send_response se mantiene
//--para no romper el codigo que ya esta presente dejaremos, los nuevos usaran error_response()
if (!function_exists('saveLog')) {
    /**
     * @deprecated Usar ErrorLogService::handle() o el nuevo helper error_response en su lugar.
     */

    function saveLog($message, $observation = null, $stack = null): object
    {
        try {
           /*  $data = [
                'user_id' => auth()->user() ? auth()->user()->id : null,
                'message' => $message,
                'observation' => $observation,
                'stack' => $stack ? json_encode($stack) : null
            ];

            $log = new \App\Models\Log();
            $log->fill($data);
            $log->save();
            return $log; */

            // Redirigimos al nuevo sistema para que quede todo unificado
            $fakeException = new \RuntimeException($message);
            $ref = ErrorLogService::handle($fakeException, $observation);
            
            $result = new stdClass();
            $result->id = $ref ?? 'N/A';
            $result->message = $message;
            $result->attributes = true; // Para compatibilidad con log_send_response
            return $result;

        } catch (\Throwable $th) {
            return new stdClass();
        }
    }
}

if (!function_exists('is_email')) {
    function is_email($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('storage_file')) {
    function storage_file($file, string $folder = '/'): string
    {
        /** Guardamos el archivo */
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
        return [
            'type' => $type,
            'file_name' => $fileInfo->getFilename(),
            'size' => $fileInfo->getSize(),

            /* Enviamos el archivo como base64 */
            'file' => 'data:application/' . $type . ';base64,' . base64_encode($fileInfo),
        ];
    }
}

if(!function_exists('log_send_response')){
    /**
     * @deprecated Usar error_response($e, 'contexto) 
     */
    function log_send_response($log){
        if (!property_exists($log, 'attributes')) {
            $log->id = '1A';
            $log->message = 'Falló el log';
        }
        if (!env('APP_DEBUG')) {
            return sendResponse(null, ['general' => 'Ha ocurrido un error durante la consulta. Código ' . $log->id], 490);
        }
        return sendResponse(null, ['general' => $log->message], 490);
    }
}

function enviarEmail($body, $subject, $email)
{
    try {

        $postParams = [
            'destinationName' => null,
            'destinationAdresse' => $email,
            'subject' => $subject,
            'htmlBody' => $body,
        ];
        $json = (object) Http::withoutVerifying()
            ->post(config('services.mail_api.url') . 'mail', $postParams)
            ->json();

        if (isset($json->message)) {
            throw new \Exception($json->message);
        }

        if (isset($json->error)) {
            throw new \Exception($json->error);
        }

        return true;
    } catch (\Exception $e) {
        //saveLog($e->getMessage(), __FUNCTION__, $e->getTrace());
        ErrorLogService::handle($e, __FUNCTION__);
        return false;
    }
}
