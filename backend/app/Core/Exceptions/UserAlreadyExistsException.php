<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

/**
 * Excepción lanzada cuando la lógica de negocio detecta un email duplicado.
 */
class UserAlreadyExistsException extends Exception
{
    protected $message = 'El usuario con ese email ya existe.';
    protected $code = 409; // HTTP Conflict

    public function __construct($message = null, $code = 409, Throwable $previous = null)
    {
        parent::__construct($message ?? $this->message, $code, $previous);
    }
}
