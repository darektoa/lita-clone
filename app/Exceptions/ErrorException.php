<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ErrorException extends Exception
{
    private $errors;

    public function __construct(
        string $message = '',
        int $code = 400, 
        $errors = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }


    public function getErrors() {
        return $this->errors;
    }
}
