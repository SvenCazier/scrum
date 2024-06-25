<?php
//src/Exceptions/PasswordsNotEqualException.php
declare(strict_types=1);

namespace Webshop\Exceptions;

use Exception;
use Throwable;

class PasswordsNotEqualException extends Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "Wachtwoorden zijn niet gelijk.";
        parent::__construct($message, $code, $previous);
    }
}
