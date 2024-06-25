<?php
//src/Services/RequestService.php
declare(strict_types=1);

namespace Webshop\Services;

class RequestService
{
    /**
     * Retrieves the value of a specific key from the $_GET array.
     *
     * @param string $key The key to retrieve from the $_GET array.
     *
     * @return string|null The value corresponding to the provided key, or null if the key does not exist in the $_GET array.
     */
    public static function getKey(string $key): ?string
    {
        return $_GET[$key] ?? null;
    }

    /**
     * Retrieves the value of a specific key from the $_POST array.
     *
     * @param string $key The key to retrieve from the $_POST array.
     *
     * @return string|null The value corresponding to the provided key, or null if the key does not exist in the $_POST array.
     */
    public static function postKey(string $key): ?string
    {
        return $_POST[$key] ?? null;
    }

    /**
     * Retrieves the entire $_GET array.
     *
     * @return array The $_GET array containing all query string parameters.
     */
    public static function getArray(): array
    {
        return $_GET;
    }

    /**
     * Retrieves the entire $_POST array.
     *
     * @return array The $_POST array containing all form data submitted via POST.
     */
    public static function postArray(): array
    {
        return $_POST;
    }

    /**
     * Checks if the current request method is GET.
     *
     * @return bool True if the request method is GET, false otherwise.
     */
    public static function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }

    /**
     * Checks if the current request method is POST.
     *
     * @return bool True if the request method is POST, false otherwise.
     */
    public static function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }
}
