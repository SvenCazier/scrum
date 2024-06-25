<?php
//src/Services/ErrorService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Services\SessionService;

class ErrorService
{

    const PDO_EXCEPTION = "Er is een onverwachte fout opgetreden. Probeer het later opnieuw.";

    /**
     * Sets an error message in the session errors array.
     *
     * @param string      $errormsg The error message to set.
     * @param string|null $key      Optional. The key under which to store the error message.
     *                              If not provided, the error message is appended to the end of the array.
     * @return void
     */
    public static function setError(string $errormsg, string $key = null): void
    {
        $errors = self::getErrors();
        if ($key) {
            $errors[$key] = $errormsg;
        } else {
            $errors[] = $errormsg;
        }

        SessionService::setSession("errors", $errors);
    }

    /**
     * Retrieves the array of error messages from the session.
     * Clears the session errors array after retrieval.
     *
     * @return array The array of error messages.
     */
    public static function getErrors(): array
    {
        $errors = SessionService::getSession("errors") ?? [];
        if ($errors) {
            self::clearErrors();
        }
        return $errors;
    }

    /**
     * Clears all error messages from the session.
     *
     * @return void
     */
    public static function clearErrors(): void
    {
        SessionService::clearSessionVariable("errors");
    }
}
