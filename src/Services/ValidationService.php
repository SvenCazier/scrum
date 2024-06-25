<?php
//src/Services/ValidationService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Services\ErrorService;
use Webshop\Exceptions\{DataTypeException, EmptyInputException, LessThanOrEqualToZeroException, PasswordsNotEqualException};

class ValidationService
{

    /**
     * Represents an email address. Value has to be a valid email.
     */
    const EMAIL = "geldig e-mailadres";
    /**
     * Represents a numeric value. Value has to be a number.
     */
    const NUMBER = "nummer";
    /**
     * Represents a text string. Value can be any text.
     */
    const TEXT = "geldige tekst";
    /**
     * Represents a password. Value has to be a valid password.
     */
    const PASSWORD = "geldig wachtwoord";
    /**
     * Represents a boolean value (yes/no value). Value has to be true or false.
     */
    const BOOLEAN = "ja/nee waarde";
    /**
     * Represents a whole number. Value has to be an integer.
     */
    const INTEGER = "heel getal";
    /**
     * Represents a floating point number. Value has to be a floating point number.
     */
    const FLOAT = "kommagetal";
    /**
     * Represents a house number. Value has to be a valid house number format.
     */
    const HOUSE_NUMBER = "geldig huisnummer";
    /**
     * Represents a phone number. Value has to be a valid phone number format.
     */
    const PHONE_NUMBER = "geldig telefoonnummer";
    /**
     * Represents a value that must not be empty. Value is not allowed to be empty.
     */
    const NOT_EMPTY = "Value is not allowed to be empty";
    /**
     * Represents a value that is not allowed to be less than or equal to zero. Value has to be greater than zero.
     */
    const NOT_LESS_THAN_OR_EQUAL_TO_ZERO = "waarde groter dan 0";
    /**
     * Represents a value that is required to be an array. Value has to be an array.
     */
    const IS_ARRAY = "Value has to be an array";

    const VAT_NUMBER = "geldig btw-nummer";

    /**
     * Validates the inputs based on specified data types and requirements.
     *
     * @param array $inputs An associative array of input values to be validated.
     * @param array $keys An associative array where keys represent input fields and values represent
     *                    an array of validation rules for each field.
     * @return array|null Returns the validated inputs if all validation rules are satisfied, or null if
     *                    any validation fails.
     *              Validation rules: 
     *                  - ValidationService::EMAIL
     *                  - ValidationService::NUMBER
     *                  - ValidationService::TEXT
     *                  - ValidationService::PASSWORD
     *                  - ValidationService::BOOLEAN
     *                  - ValidationService::INTEGER
     *                  - ValidationService::FLOAT
     *                  - ValidationService::HOUSE_NUMBER
     *                  - ValidationService::PHONE_NUMBER
     *                  - ValidationService::NOT_EMPTY
     *                  - ValidationService::NOT_LESS_THAN_OR_EQUAL_TO_ZERO
     *                  - ValidationService::IS_ARRAY
     *
     * @example
     *   $originalInputs = [
     *       'email' => 'example@example.com',
     *       'password' => 'password123'
     *   ];
     *   $validatedInputs = ValidationService::validateInputs($originalInputs, [
     *       'email' => [ValidationService::NOT_EMPTY, ValidationService::EMAIL],
     *       'password' => [ValidationService::NOT_EMPTY, ValidationService::PASSWORD]
     *   ]);
     *
     * @see ValidationService::EMAIL
     * @see ValidationService::NUMBER
     * @see ValidationService::TEXT
     * @see ValidationService::PASSWORD
     * @see ValidationService::BOOLEAN
     * @see ValidationService::INTEGER
     * @see ValidationService::FLOAT
     * @see ValidationService::HOUSE_NUMBER
     * @see ValidationService::PHONE_NUMBER
     * @see ValidationService::NOT_EMPTY
     * @see ValidationService::NOT_LESS_THAN_OR_EQUAL_TO_ZERO
     * @see ValidationService::IS_ARRAY
     */

    public static function validateInputs(array $inputs, array $keys): ?array
    {
        if (empty($keys)) return null;
        $inputs = self::sanitizeInput($inputs);
        if (self::emptyCheckFailed($inputs, $keys)) return null;
        if (self::arrayCheckFailed($inputs, $keys)) return null;
        if (self::greaterThanZeroFailed($inputs, $keys)) return null;

        $areDataTypesValid = true;
        foreach ($keys as $key => $dataTypes) {
            $dataTypes = array_filter($dataTypes, function ($dataType) {
                return $dataType !== self::NOT_EMPTY && $dataType !== self::IS_ARRAY;
            });

            if (is_array($inputs[$key])) {
                foreach ($inputs[$key] as $input) {
                    if (self::isInvalidDataType($key, $input, $dataTypes)) $areDataTypesValid = false;
                }
            } else {
                if (self::isInvalidDataType($key, $inputs[$key], $dataTypes)) $areDataTypesValid = false;
            }
        }
        if ($areDataTypesValid) return $inputs;
        return null;
    }

    /**
     * Checks if two passwords are equal.
     *
     * @param string $password1 The first password to compare.
     * @param string $password2 The second password to compare.
     * @return bool Returns true if the passwords are equal, false otherwise.
     */
    public static function passwordsEqual(string $password1, string $password2): bool
    {
        try {
            if ($password1 !== $password2) throw new PasswordsNotEqualException();
            return true;
        } catch (PasswordsNotEqualException $e) {
            ErrorService::setError($e->getMessage(), "passwords_not_equal");
            return false;
        }
    }

    /**
     * Converts each element of a string array to an integer.
     *
     * @param array $array The array containing string elements to be converted to integers.
     * @return array Returns a new array with each element converted to an integer.
     */
    public static function convertStringArrayToIntArray(array $array): array
    {
        return array_map('intval', $array);
    }

    private static function sanitizeInput(array $inputs): array
    {
        foreach ($inputs as $key => $value) {
            if (is_array($inputs[$key])) {
                foreach ($inputs[$key] as $arrKey => $arrValue) {
                    $inputs[$key][$arrKey] = stripslashes(htmlspecialchars(trim($arrValue ?? ""), ENT_QUOTES));
                }
            } else {
                $inputs[$key] = stripslashes(htmlspecialchars(trim($value ?? ""), ENT_QUOTES));
            }
        }
        return $inputs;
    }

    private static function emptyCheckFailed(array $inputs, array $keys): bool
    {
        $isValid = true;
        foreach ($keys as $key => $value) {
            if (!array_key_exists($key, $inputs)) return true;
            try {
                if (empty($inputs[$key]) && in_array(self::NOT_EMPTY, $value)) {
                    throw new EmptyInputException();
                }
            } catch (EmptyInputException $e) {
                ErrorService::setError($e->getMessage(), $key);
                $isValid = false;
            }
        }
        return !$isValid;
    }

    private static function arrayCheckFailed(array $inputs, array $keys): bool
    {
        $isValid = true;
        foreach ($keys as $key => $value) {
            if (!array_key_exists($key, $inputs)) return true;
            try {
                if ((is_array($inputs[$key]) xor in_array(self::IS_ARRAY, $value))) {
                    throw new DataTypeException();
                }
            } catch (DataTypeException $e) {
                ErrorService::setError($e->getMessage(), $key);
                $isValid = false;
            }
        }
        return !$isValid;
    }

    private static function greaterThanZeroFailed(array $inputs, array $keys): bool
    {
        $isValid = true;
        foreach ($keys as $key => $value) {
            try {
                if ($inputs[$key] <= 0 && in_array(self::NOT_LESS_THAN_OR_EQUAL_TO_ZERO, $value)) {
                    throw new LessThanOrEqualToZeroException();
                }
            } catch (LessThanOrEqualToZeroException $e) {
                ErrorService::setError($e->getMessage(), $key);
                $isValid = false;
            }
        }
        return !$isValid;
    }

    private static function isInvalidDataType($key, $input, $dataTypes): bool
    {
        try {
            if (empty($dataTypes)) return false;
            foreach ($dataTypes as $dataType) {
                if (match ($dataType) {
                    self::BOOLEAN => filter_var($input, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) === null ? false : true,
                    self::EMAIL => filter_var($input, FILTER_VALIDATE_EMAIL),
                    self::NUMBER => is_numeric($input),
                    self::FLOAT => self::isFloat($input),
                    self::INTEGER => self::isInt($input),
                    self::TEXT => is_string($input),
                    self::PASSWORD => self::isPassword($input),
                    self::HOUSE_NUMBER => self::isHouseNumber($input),
                    self::PHONE_NUMBER => self::isPhoneNumber($input),
                    self::VAT_NUMBER => self::isVatNumber($input),
                }) {
                    return false;
                }
            }
            throw new DataTypeException($dataTypes);
        } catch (DataTypeException $e) {
            ErrorService::setError($e->getMessage(), $key);
            return true;
        }
    }

    private static function isInt(string $input): bool
    {
        if (is_numeric($input)) {
            return is_int(1 * $input);
        }
        return false;
    }

    private static function isFloat(string $input): bool
    {
        if (is_numeric($input)) {
            return is_float(1 * $input);
        }
        return false;
    }

    private static function isPassword(string $input): bool
    {
        // Implement more specific password validation if necessary
        return is_string($input);
    }

    private static function isHouseNumber(string $input): bool
    {
        $pattern = '/^\d+[A-Za-z]?$/';
        return preg_match($pattern, $input) === 1;
    }

    private static function isPhoneNumber(string $input): bool
    {
        if (self::isInt($input) && strlen($input) > 8 && strlen($input) < 11) {
            return true;
        }
        return false;
    }

    private static function isVatNumber(string $input): bool
    {
        $pattern = '/^0\d{9}$/';
        return preg_match($pattern, $input) === 1;
    }
}
