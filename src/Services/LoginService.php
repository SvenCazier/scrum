<?php
//src/Services/LoginService.php
declare(strict_types=1);

namespace Webshop\Services;

use Exception;
use PDOException;
use Webshop\Data\UserDAO;
use Webshop\Entities\User;
use Webshop\Exceptions\AuthenticationException;
use Webshop\Services\{AddressService, AuthenticationService, ErrorService};

class LoginService
{
    /**
     * Attempts to authenticate the user using the provided email and password.
     *
     * @param string $email The user's email address.
     * @param string $password The user's password.
     * @throws PDOException If an error occurs while accessing the database.
     * @throws AuthenticationException If authentication fails due to invalid credentials.
     */
    public function login(string $email, string $password): void
    {
        try {
            // Retrieve user login information from the database using the email
            $userDAO = new UserDAO();
            $loginInfo = $userDAO->getUserLoginInfo($email);

            // Check if login information exists and if the provided password matches
            if (!$loginInfo || $loginInfo["paswoord"] !== $password) {
                // If no active user is found or passwords don't match, throw an authentication exception
                throw new AuthenticationException();
            }

            // Retrieve additional user information based on the login details
            $userInfo = $userDAO->getUserInfoById($loginInfo["gebruikersAccountId"]);

            // Retrieve address information for billing and delivery addresses
            $addressService = new AddressService();
            $facturatieAdres = $addressService->getAddressById((int) $userInfo["facturatieAdresId"]);
            $leveringsAdres = $addressService->getAddressById((int) $userInfo["leveringsAdresId"]);

            // Create a User object with the retrieved information
            $user = new User(
                (int) $loginInfo["gebruikersAccountId"],
                $email,
                (int) $userInfo["klantId"],
                $userInfo["voornaam"],
                $userInfo["familienaam"],
                $facturatieAdres,
                $leveringsAdres,
                $userInfo["functie"],
                $userInfo["bedrijfsNaam"],
                $userInfo["btwNummer"]
            );

            // Store the authenticated user in the session
            AuthenticationService::login($user);
        } catch (Exception $e) {
            $errorMessage = "";
            if ($e instanceof PDOException) {
                $errorMessage = ErrorService::PDO_EXCEPTION;
            } elseif ($e instanceof AuthenticationException) {
                $errorMessage = $e->getMessage();
            }
            ErrorService::setError($errorMessage, "otherLoginError");
        }
    }
}
