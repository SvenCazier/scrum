<?php

declare(strict_types=1);
//src/Data/UserDAO.php
namespace Webshop\Data;

use PDOException;
use Webshop\Data\DBConnection;
use Webshop\Exceptions\PasswordsNotEqualException;

class UserDAO extends DBConnection
{
    /**
     * Retrieves login information for a user based on their email address.
     *
     * @param string $email The email address of the user.
     * @return array|false An associative array containing user login information if found, or false if not found.
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function getUserLoginInfo(string $email): array|bool
    {
        $sql = "SELECT
                    gebruikersAccountId,
                    paswoord
                FROM gebruikersaccounts
                WHERE emailadres = :email AND disabled = 0";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["email" => $email]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function findUserByEmail(string $email): array|bool
    {
        $sql = "SELECT
                    gebruikersAccountId,
                    paswoord
                FROM gebruikersaccounts
                WHERE emailadres = :email";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["email" => $email]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Retrieves user information by their unique identifier.
     *
     * @param int $id The unique identifier of the user.
     * @return array|false An associative array containing user information if found, or false if not found.
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function getUserInfoById(int $id): array|bool
    {
        $sql = "SELECT 
                    g.gebruikersAccountId,
                    g.emailadres,
                    COALESCE(c.voornaam, n.voornaam) AS voornaam,
                    COALESCE(c.familienaam, n.familienaam) AS familienaam,
                    c.functie,
                    r.naam AS bedrijfsNaam,
                    r.btwNummer,
                    k.klantId,
                    k.facturatieAdresId,
                    k.leveringsAdresId
                FROM gebruikersaccounts AS g
                LEFT JOIN contactpersonen AS c ON g.gebruikersAccountId = c.gebruikersAccountId
                LEFT JOIN natuurlijkepersonen AS n ON g.gebruikersAccountId = n.gebruikersAccountId
                LEFT JOIN rechtspersonen AS r ON c.klantId = r.klantId OR n.klantId = r.klantId
                LEFT JOIN klanten AS k ON c.klantId = k.klantId OR n.klantId = k.klantId
                WHERE g.gebruikersAccountId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * Creates a new user account with given email and password.
     *
     * @param string $email Email of the new user account
     * @param string $password Password of the new user account
     * @param int $disabled Disabled (guest) = 1 || abled (active client account) = 0
     * @return string $userAccountId Returns the user account ID of the new account
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function createUserAccount(string $email, string $password, int $disabled = 0): int
    {
        $sql = "INSERT INTO gebruikersaccounts 
                    (emailadres, paswoord, disabled) 
                VALUES (:email, :password, :disabled)";
        try {
            $this->connect();
            $userAccountId = $this->create(
                $sql,
                [
                    "email" => $email,
                    "password" => $password,
                    "disabled" => $disabled,
                ]
            );
            $this->disconnect();
            return $userAccountId;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * Creates a new guest account with given email.
     *
     * @param string $email Email of the new user account.
     * @return string $guestUserAccountId Returns the guest account ID of the new account.
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function createGuestUserAccount(string $email): int
    {
        $sql = "INSERT INTO gebruikersaccounts 
                    (emailadres, disabled) 
                VALUES 
                    (:email, 1)";
        try {
            $this->connect();
            $guestUserAccountId = $this->create(
                $sql,
                ["email" => $email]
            );
            $this->disconnect();
            return $guestUserAccountId;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * Creates a new natural person in the DB.
     *
     * @param int $clientId The client account ID to be linked with the natural person. 
     * @param string $firstname First name of the natural person.
     * @param string $surname Familiy name of the natural person.
     * @param int $userAccountId User account of the client to be linked with the natural person.
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function createNaturalPerson(int $clientId, string $firstname, string $surname, int $userAccountId)
    {
        $sql = "INSERT INTO natuurlijkepersonen 
                    (klantId, voornaam, familienaam, gebruikersAccountId) 
                VALUES 
                    (:klantId, :voornaam, :familienaam, :gebruikersAccountId)";
        try {
            $this->connect();
            $naturalPersonId = $this->create(
                $sql,
                [
                    "klantId" => $clientId,
                    "voornaam" => $firstname,
                    "familienaam" => $surname,
                    "gebruikersAccountId" => $userAccountId,
                ]
            );
            $this->disconnect();
            return $naturalPersonId;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * Creates a new legal person in the DB.
     *
     * @param int $clientId The client account ID to be linked with the legal person. 
     * @param string $name Name of the company.
     * @param string $btwNumber BTW number of the company.
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function createLegalPerson(int $clientId, string $name, string $btwNumber)
    {
        $sql = "INSERT INTO rechtspersonen 
                    (klantId, naam, btwNummer)
                VALUES 
                    (:klantId, :naam, :btwNummer)";
        try {
            $this->connect();
            $legalPersonId = $this->create(
                $sql,
                [
                    "klantId" => $clientId,
                    "naam" => $name,
                    "btwNummer" => $btwNumber
                ]
            );
            $this->disconnect();
            return $legalPersonId;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * Creates a new contact person in the DB linked to existing client and user account.
     *
     * @param string $firstname First name of the contact person.
     * @param string $surname Familiy name of the contact person.     
     * @param string $function Function of the contact person. 
     * @param int $clientId The client account ID to be linked with the contact person. 
     * @param int $userAccountId The user account ID to be linked with the contact person. 
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function createContactPerson(string $firstname, string $surname, string $function, int $clientId, int $userAccountId)
    {
        $sql = "INSERT INTO contactpersonen 
                    (voornaam, familienaam, functie, klantId, gebruikersAccountId)
                VALUES 
                    (:voornaam, :familienaam, :functie, :klantId, :gebruikersAccountId)";
        try {
            $this->connect();
            $contactPersonId = $this->create(
                $sql,
                [
                    "voornaam" => $firstname,
                    "familienaam" => $surname,
                    "functie" => $function,
                    "klantId" => $clientId,
                    "gebruikersAccountId" => $userAccountId,
                ]
            );
            $this->disconnect();
            return $contactPersonId;
        } catch (PDOException $e) {
            throw $e;
        }
    }


    /**
     * Creates a new client user account with invoice adress ID and delivery address ID  .
     *
     * @param int $invoiceAddresId ID of the invoice address
     * @param int $deliveryAddressId ID of the delivery address
     * @return string $clientId Returns the unique ID of the new client account
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function createClient(int $invoiceAddresId, int $deliveryAddressId)
    {
        $sql = "INSERT INTO klanten 
                    (facturatieAdresId, leveringsAdresId) 
                VALUES 
                    (:facturatieAdresId, :leveringsAdresId)";
        try {
            $this->connect();
            $clientId = $this->create(
                $sql,
                [
                    "facturatieAdresId" => $invoiceAddresId,
                    "leveringsAdresId" => $deliveryAddressId,
                ]
            );
            $this->disconnect();
            return $clientId;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Update the password of account with given unique ID.
     *
     * @param int $userAccountId ID of the user account.
     * @param string $password The password to replace the old with.
     * @param string $passwordConfirm Confirm the new password.
     * @throws PDOException If an error occurs while accessing the database.
     */
    public function updateUserPassword(int $userAccountId, string $password): int
    {
        $sql = "UPDATE 
                    gebruikersaccounts 
                SET 
                    paswoord = :paswoord, 
                    disabled = 0
                WHERE 
                    gebruikersAccountId = :gebruikersAccountId";

        try {
            $this->connect();
            $result = $this->update(
                $sql,
                [
                    "paswoord" => $password,
                    "gebruikersAccountId" => $userAccountId,
                ]
            );
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            echo print_r($e);
            throw $e;
        }
    }
}
