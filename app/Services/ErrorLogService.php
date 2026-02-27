<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ErrorLogService
{
    protected static array $ignoreExceptions = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
    ];

    protected static array $lowPriorityExceptions = [
        \Illuminate\Session\TokenMismatchException::class,
        \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException::class,
    ];

    //METODOS UTILIZADOS EN EL HANDLE

    // Determina si la excepcion debe ser completamente ignorada
    protected static function shouldIgnore(Throwable $e): bool
    {
        foreach (self::$ignoreExceptions as $ignored) {
            if ($e instanceof $ignored) {
                return true;
            }
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $statusCode = $e->getStatusCode();
            if ($statusCode >= 400 && $statusCode < 500) {
                return true;
            }
        }
        return false;
    }
    // Determina si es un error de baja prioridad que no se notificará a Discord
    protected static function isLowPriority(Throwable $e): bool
    {
        foreach (self::$lowPriorityExceptions as $low) {
            if ($e instanceof $low) {
                return true;
            }
        }
        return false;
    }

    //clasifica la severidad del error para poder fitlrarlo despues
    protected static function classifySeverity(Throwable $e): string
    {
        return match (true) {
            $e instanceof \Illuminate\Database\QueryException,
            $e instanceof \PDOException => 'critical',

            $e instanceof \Illuminate\Http\Client\ConnectionException,
            $e instanceof \Illuminate\Http\Client\RequestException,
            $e instanceof \ErrorException => 'high',

            $e instanceof \RuntimeException => 'medium',

            default => 'low',
        };
    }

    //generar un codigo unico, verificar que no exista en la BD.
    protected static function generateUniqueReference(): string
    {
        // 4 chars random + microsegundos = colision practicamente imposible )
        return 'ERR-' . strtoupper(Str::random(4)) . '-' . substr(now()->format('His') . now()->format('u'), 0, 9);
    }

    protected static function getSafeUser(): ?object
    {
        try {
            return auth()->check() ? auth()->user() : null;
        } catch (Throwable) {
            return null;
        }
    }
    protected static function getSafeUserId(): ?int
    {
        return self::getSafeUser()?->id;
    }


    // Construye el array de propiedades para el log de Spatie
    protected static function buildProperties(Throwable $e, ?string $context): array
    {
        return [
            'context' => $context,
            'exception' => [
                'class' => get_class($e),
                'message' => Str::limit($e->getMessage(), 500),
                'code' => $e->getCode(),
                'file' => str_replace(base_path(), '', $e->getFile()),
                'line' => $e->getLine(),
            ],
            'request' => [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'input' => self::sanitizeInput(
                    request()->except(['password', 'password_confirmation', 'token', 'secret', 'authorization', 'cookie'])
                ),
            ],
        ];
    }

    /**
     * Tiene el fin de limpiar los datos de entrada para evitar guardar información sensible o demasiado voluminosa en los logs.
     */
    protected static function sanitizeInput(array $input): array
    {
        $sanitized = [];    

        foreach ($input as $key => $value) {
            if (Str::contains(strtolower($key), ['password', 'secret', 'token', 'key', 'credential', 'auth'])) {
                continue;
            }
            if (is_string($value)) {
                $sanitized[$key] = Str::limit($value, 200);
            } elseif (is_array($value)) {
                $sanitized[$key] = '[array]';
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
    /**
     * Mandamos al discord el error
     */
    private static function notifyDiscord(Throwable $e, string $reference, ?string $context): void
    {
        try {
            $webhookUrl = config('services.discord.webhook_url');
            if (!$webhookUrl) {
                return;
            }

            $severity = self::classifySeverity($e);

            $emoji = match ($severity) {
                'critical' => '🔴', 'high' => '🟠', 'medium' => '🟡', 'low' => '🔵', default => '⚪',
            };
            $color = match ($severity) {
                'critical' => 15158332, 'high' => 16744448, 'medium' => 16776960, 'low' => 3447003, default => 9807270,
            };

            $userId = self::getSafeUserId();

            Http::timeout(2)->connectTimeout(1)->withoutVerifying()->post($webhookUrl, [
                'content' => "{$emoji} **Error [{$severity}]** en **" . config('app.name') . "**",
                'embeds' => [
                    [
                        'title' => "Ref: {$reference}",
                        'description' => '```' . Str::limit($e->getMessage(), 200) . '```',
                        'color' => $color,
                        'fields' => array_values(array_filter([
                            ['name' => 'Tipo', 'value' => '`' . class_basename($e) . '`', 'inline' => true],
                            ['name' => 'Severidad', 'value' => '`' . $severity . '`', 'inline' => true],
                            $context ? ['name' => 'Contexto', 'value' => '`' . $context . '`', 'inline' => true] : null,
                            ['name' => 'Archivo', 'value' => '`' . basename($e->getFile()) . ':' . $e->getLine() . '`', 'inline' => true],
                            ['name' => 'URL', 'value' => '`' . request()->method() . ' /' . request()->path() . '`', 'inline' => true],
                            ['name' => 'Usuario', 'value' => '`' . ($userId ?? 'No autenticado') . '`', 'inline' => true],
                        ])),
                        'footer' => ['text' => config('app.name') . ' | ' . config('app.env')],
                        'timestamp' => now()->toIso8601String(),
                    ]
                ],
            ]);
        } catch (Throwable) {
            //si Discord falla, no romper nada
        }
    }
    //FIN METODOS UTILIZADOS EN EL HANDLE

    /**
     * Punto para manejar cualquier error o excepcion que ocurra en la aplicacion.
     * @param Throwable $e Cualquier error o excepcion que se quiera manejar.
     * @param  string|null  $context  Contexto adicional (ej: nombre del method, module, etc.)
     * @return string|null  Codigo de referencia del error, o null si fue ignorado.
     * 
     */


    public static function handle(Throwable $e, ?string $context = null): ?string
    {
        if (self::shouldIgnore($e)) {
            return null;
        }

        $errorReference = self::generateUniqueReference();
        $severity = self::classifySeverity($e);
        $user = self::getSafeUser();

        try {
            activity('system-errors')
                ->causedBy($user)
                ->withProperties(self::buildProperties($e, $context))
                ->tap(function ($activity) use ($errorReference, $severity) {
                    $activity->reference_code = $errorReference;
                    $activity->event = $severity;
                })
                ->log(Str::limit($e->getMessage(), 255));
        } catch (Throwable $logExc) {

            Log::channel('single')->error('FALLO DOBLE: Error original + Fallo de DB', [
                '1_ERROR_ORIGINAL' => $e->getMessage(),
                '2_ERROR_DE_LA_DB' => $logExc->getMessage(),
                'REFERENCIA' => $errorReference,
                'ARCHIVO' => $e->getFile() . ':' . $e->getLine()
            ]);
        }

        // cambiar esto en produccion 
        if (!self::isLowPriority($e) && config('app.env') === 'produccion') {
            self::notifyDiscord($e, $errorReference, $context);
        }

        return $errorReference;
    }

}