<?php

namespace App\Exceptions;

class ForbiddenActionException extends DomainException
{
    public function __construct(string $message = 'Ação não permitida.', array $context = [])
    {
        parent::__construct($message, 0, null, $context);
    }
}
