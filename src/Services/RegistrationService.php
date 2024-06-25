<?php
//src/Services/RegistrationService.php
declare(strict_types=1);

namespace Webshop\Services;

use Exception;
use PDOException;
use Webshop\Data\{UserDAO};
use Webshop\Entities\{Address, Place, User};
use Webshop\Exceptions\{AuthenticationException, EmailExistsException};
use Webshop\Services\{AddressService, AuthenticationService, ErrorService, SessionService};

class RegistrationService
{
    /**
     * Attempts to authenticate the user using the provided email and password.
     *
     * @param string $email The user's email address.
     * @param string $password The user's password.
     * @throws PDOException If an error occurs while accessing the database.
     * @throws AuthenticationException If authentication fails due to invalid credentials.
     */
    public function registerUser(array $userInfo): void
    {
        try {
            // create new user account in db with email and password and set disabled to 0, return $gebruikersAccountId
            $userDAO = new UserDAO();
            $userAccountId = $userDAO->createUserAccount($userInfo["register_email"], $userInfo["register_password"]);

            // create address with provided user information
            $addresses = $this->createAddresses($userInfo);
            // create new user object with addresses and userAccountId
            $user = new User(
                (int) $userAccountId,
                $userInfo["register_email"],
                Null,
                $userInfo["first_name"],
                $userInfo["last_name"],
                $addresses["invoiceAddress"],
                $addresses["deliveryAddress"],
                $userInfo["company_role"] ?? null,
                $userInfo["company_name"] ?? null,
                $userInfo["vat_number"] ?? null,
            );
            // create a new client, before logging in the user
            AuthenticationService::login($this->createClient($user));
        } catch (Exception $e) {
            SessionService::setSession("registrationData", $userInfo);
            $errorMessage = "";
            if ($e instanceof PDOException) {
                $errorMessage = ErrorService::PDO_EXCEPTION;
                if ($e->getCode() === "23000") {
                    $errorMessage = "Dit e-mailadres is reeds in gebruik.";
                }
            }
            ErrorService::setError($errorMessage, "otherRegistrationError");
        }
    }

    public function registerUserWithoutPassword(user $guestUser): User
    {

        try {
            // create new user account in db with email, no password and set disabled to 1, add returned gebruikersAccountId to $guestUser
            $userDAO = new UserDAO();

            $userAccountId = $userDAO->createGuestUserAccount($guestUser->getEmailadres());
            $guestUser->setId((int) $userAccountId);

            $addressService = new AddressService();
            $invoiceAddress = $addressService->createAddress(
                $guestUser->getFacturatieAdres()->getStraat(),
                $guestUser->getFacturatieAdres()->getHuisNummer(),
                $guestUser->getFacturatieAdres()->getBus(),
                $guestUser->getFacturatieAdres()->getPlaats()->getPostcode(),
                $guestUser->getFacturatieAdres()->getPlaats()->getPlaats()
            );
            // Check if delivery address is different from invoice address
            $deliveryAddress = NULL;
            if (
                $guestUser->getFacturatieAdres()->getStraat() !== $guestUser->getLeveringsAdres()->getStraat() ||
                $guestUser->getFacturatieAdres()->getHuisNummer() !== $guestUser->getLeveringsAdres()->getHuisNummer() ||
                $guestUser->getFacturatieAdres()->getBus() !== $guestUser->getLeveringsAdres()->getBus() ||
                $guestUser->getFacturatieAdres()->getPlaats()->getPostcode() !== $guestUser->getLeveringsAdres()->getPlaats()->getPostcode() ||
                $guestUser->getFacturatieAdres()->getPlaats()->getPlaats() !== $guestUser->getLeveringsAdres()->getPlaats()->getPlaats()
            ) {
                // Create delivery address only if it's different
                $deliveryAddress = $addressService->createAddress(
                    $guestUser->getLeveringsAdres()->getStraat(),
                    $guestUser->getLeveringsAdres()->getHuisNummer(),
                    $guestUser->getLeveringsAdres()->getBus(),
                    $guestUser->getLeveringsAdres()->getPlaats()->getPostcode(),
                    $guestUser->getLeveringsAdres()->getPlaats()->getPlaats()
                );
            } else {
                // Set delivery address to the same as the invoice address
                $deliveryAddress = $invoiceAddress;
            }

            $guestUser->setFacturatieAdres($invoiceAddress);
            $guestUser->setLeveringsAdres($deliveryAddress);

            return $this->createClient($guestUser);
        } catch (Exception $e) {
            $errorMessage = "";
            if ($e instanceof PDOException) {
                $errorMessage = ErrorService::PDO_EXCEPTION;
            }
            ErrorService::setError($errorMessage, "otherRegistrationError");
        }
    }

    private function createClient(user $user): User
    {
        try {
            // create new client with user object and return ID of created account
            $userDAO = new UserDAO();
            $clientId = $userDAO->createClient($user->getFacturatieAdres()->getId(), $user->getLeveringsAdres()->getid());

            // update user object with retrieved client ID and return it
            $user->setKlantId((int) $clientId);

            if ($user->getBtwNummer()) {
                $userDAO->createLegalPerson($user->getKlantId(), $user->getBedrijfsNaam(), $user->getBtwNummer());
                $userDAO->createContactPerson($user->getVoornaam(), $user->getFamilienaam(), $user->getFunctie(), $user->getKlantId(), $user->getId());
            } else {
                $userDAO->createNaturalPerson($user->getKlantId(), $user->getVoornaam(), $user->getFamilienaam(), $user->getId());
            }

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function addPasswordToAccount(User $user, string $password): void
    {
        $userDAO = new UserDAO();
        if ($userDAO->updateUserPassword($user->getId(), $password)) {
            AuthenticationService::login($user);
        }
    }

    public function createGuestUser(array $userInfo): bool
    {
        try {
            $userDAO = new UserDAO();

            if ($userDAO->findUserByEmail($userInfo["register_email"])) {
                throw new EmailExistsException();
            }

            // look up zip code and location to get id? and create new place object
            $invoicePlace = new Place(-1, $userInfo["invoice_zip_code"], $userInfo["invoice_location"]);
            $deliveryPlace = new Place(-1, $userInfo["delivery_zip_code"], $userInfo["delivery_location"]);

            // create new user with data and store in session
            $guestUser = new User(
                -1, // non existing user account id
                $userInfo["register_email"],
                -1, // non existing client id
                $userInfo["first_name"],
                $userInfo["last_name"],
                new Address(-1, $userInfo["invoice_street"], $userInfo["invoice_house_number"], $invoicePlace, true, $userInfo["invoice_appendix"]),
                new Address(-1, $userInfo["delivery_street"], $userInfo["delivery_house_number"], $deliveryPlace, true, $userInfo["delivery_appendix"]),
                $userInfo["company_role"] ?? NULL,
                $userInfo["company_name"] ?? NULL,
                $userInfo["vat_number"] ?? NULL
            );
            SessionService::setSession("guestUser", $guestUser);
            return true;
        } catch (Exception $e) {
            ErrorService::setError($e->getMessage(), "otherRegistrationError");
            return false;
        }
    }

    private function createAddresses(array $userInfo): array
    {
        try {
            $addressService = new AddressService();
            $invoiceAddress = $addressService->createAddress(
                $userInfo["invoice_street"],
                $userInfo["invoice_house_number"],
                isset($userInfo["invoice_appendix"]) ? $userInfo["invoice_appendix"] : "",
                $userInfo["invoice_zip_code"],
                $userInfo["invoice_location"]
            );
            // Check if delivery address is different from invoice address
            $deliveryAddress = NULL;
            if (
                $userInfo["delivery_street"] !== $userInfo["invoice_street"] ||
                $userInfo["delivery_house_number"] !== $userInfo["invoice_house_number"] ||
                (isset($userInfo["delivery_appendix"]) && $userInfo["delivery_appendix"] !== $userInfo["invoice_appendix"]) ||
                $userInfo["delivery_zip_code"] !== $userInfo["invoice_zip_code"] ||
                $userInfo["delivery_location"] !== $userInfo["invoice_location"]
            ) {
                // Create delivery address only if it's different
                $deliveryAddress = $addressService->createAddress(
                    $userInfo["delivery_street"],
                    $userInfo["delivery_house_number"],
                    isset($userInfo["delivery_appendix"]) ? $userInfo["delivery_appendix"] : "",
                    $userInfo["delivery_zip_code"],
                    $userInfo["delivery_location"]
                );
            } else {
                // Set delivery address to the same as the invoice address
                $deliveryAddress = $invoiceAddress;
            }

            return ["invoiceAddress" => $invoiceAddress, "deliveryAddress" => $deliveryAddress];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
