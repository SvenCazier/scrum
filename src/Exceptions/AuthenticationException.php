<?php
//src/Exceptions/AuthenticationException.php
declare(strict_types=1);

namespace Webshop\Exceptions;

use Exception;
use Throwable;

class AuthenticationException extends Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "De ingevoerde inloggegevens zijn onjuist. Probeer het opnieuw.";
        parent::__construct($message, $code, $previous);
    }
}
