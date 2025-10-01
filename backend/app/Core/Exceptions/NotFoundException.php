<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción personalizada para errores de "Recurso no encontrado" (404).
 * Se usa en la capa de Servicio para indicar que una Entidad no pudo ser recuperada.
 * Esto permite al controlador capturarla y devolver el código de estado HTTP 404.
 */
class NotFoundException extends Exception
{
    public function __construct(string $message = "Resource not found.", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
