<?php

namespace Dgudovic\Framework\Console;

use Exception;

class ConsoleException extends Exception
{
    public function __construct(
        string $message = 'An error occurred while executing the command',
        int $code = 0
    )
    {
        parent::__construct($message, $code);
    }
}