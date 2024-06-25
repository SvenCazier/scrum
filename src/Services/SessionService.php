<?php
//src/Services/SessionService.php
declare(strict_types=1);

namespace Webshop\Services;

class SessionService
{
    /**
     * Set a session variable.
     *
     * @param string $key The key of the session variable.
     * @param mixed $value The value of the session variable.
     * @param bool $wantsToBeSerialized Whether the value should be serialized before storing (optional, default = false).
     * @return void
     */
    public static function setSession(string $key, mixed $value, bool $wantsToBeSerialized = false): void
    {
        if (self::needsToBeSerialized($value) || $wantsToBeSerialized) {
            $value = serialize($value);
        }
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key The key of the session variable.
     * @return mixed|null The value of the session variable, or null if it doesn't exist.
     */
    public static function getSession(string $key): mixed
    {
        $value = $_SESSION[$key] ?? null;
        if ($value && self::isSerialized($value)) {
            $value = unserialize($value);
        }
        return $value;
    }

    /**
     * Clear a session variable.
     *
     * @param string $key The key of the session variable to clear.
     * @return void
     */
    public static function clearSessionVariable(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy all session variables.
     *
     * @return void
     */
    public static function destroySession(): void
    {
        $_SESSION = [];
    }

    /**
     * Determine if a value needs to be serialized.
     *
     * @param mixed $value The value to check.
     * @return bool True if the value needs to be serialized, otherwise false.
     */
    private static function needsToBeSerialized(mixed $value): bool
    {
        if (is_object($value) || (is_array($value) && count($value) > 0 && is_object($value[key($value)]))) {
            return true;
        }
        return false;
    }

    /**
     * Determine if a value is serialized.
     *
     * @param mixed $value The value to check.
     * @return bool True if the value is serialized, otherwise false.
     */
    private static function isSerialized(mixed $value): bool
    {
        if (!is_string($value) && !empty($value)) {
            return false;
        }
        if ($value === 'b:0;') {
            $result = false;
            return true;
        }

        $length = strlen($value);
        $end = "";

        switch ($value[0]) {
            case 's':
                if ($value[$length - 2] !== '"') {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';

                if ($value[1] !== ':') {
                    return false;
                }

                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;

                    default:
                        return false;
                }
            case 'N':
                $end .= ';';

                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
                break;

            default:
                return false;
        }

        if (($result = @unserialize($value)) === false) {
            $result = null;
            return false;
        }
        return true;
    }
}
