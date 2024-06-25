<?php
//src/Exceptions/EmailExistsException.php
declare(strict_types=1);

namespace Webshop\Exceptions;

use Exception;
use Throwable;

class EmailExistsException extends Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "Dit e-mailadres is reeds in gebruik.";
        parent::__construct($message, $code, $previous);
    }
}
