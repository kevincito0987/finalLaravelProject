<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción lanzada cuando un registro de UserProgress 
 * no puede ser encontrado en el sistema.
 */
class ProgressNotFoundException extends Exception
{
    /**
     * Constructor de la excepción.
     * * @param string $message Mensaje de error, por defecto "User progress not found."
     * @param int $code Código HTTP (generalmente 404 para no encontrado)
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "User progress not found.", int $code = 404, ?Throwable $previous = null)
    {
        // Puedes personalizar el mensaje si quieres incluir el ID que falló.
        // Ejemplo: $message = "User progress with ID {$id} not found."
        
        parent::__construct($message, $code, $previous);
    }
}
