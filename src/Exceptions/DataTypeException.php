<?php
//src/Exceptions/DataTypeException.php
declare(strict_types=1);

namespace Webshop\Exceptions;

use Exception;
use Throwable;

class DataTypeException extends Exception
{
    public function __construct(array $dataTypes = [], int $code = 0, Throwable $previous = null)
    {
        $message = "Er werd een verkeerde waarde ingevuld.";
        if ($dataTypes) {
            $dataTypesString = implode(', ', array_map(function ($dataType) {
                return $dataType;
            }, $dataTypes));
            $indexOfLastComma = strrpos($dataTypesString, ',');
            if ($indexOfLastComma) {
                $dataTypesString = substr_replace($dataTypesString, ' or', $indexOfLastComma, 1);
            }
            $message = sprintf("Gelieve een %s in te vullen.", $dataTypesString);
        }
        parent::__construct($message, $code, $previous);
    }
}
