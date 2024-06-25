<?php
//src/Exceptions/EmptyInputException.php
declare(strict_types=1);

namespace Webshop\Exceptions;

use Exception;
use Throwable;

class EmptyInputException extends Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "Gelieve dit veld in te vullen.";
        parent::__construct($message, $code, $previous);
    }
}
