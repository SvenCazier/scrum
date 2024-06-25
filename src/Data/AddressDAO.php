<?php

declare(strict_types=1);
//src/Data/AddressDAO.php
namespace Webshop\Data;

use PDOException;
use Webshop\Data\DBConnection;

class AddressDAO extends DBConnection
{
    public function getAddressById(int $id): array
    {
        $sql = "SELECT
                    adresId,
                    straat,
                    huisNummer,
                    bus,
                    plaatsId,
                    actief
                FROM
                    adressen
                WHERE 
                    adresId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getPlaceById(int $id): array
    {
        $sql = "SELECT
                    plaatsId,
                    postcode,
                    plaats
                FROM
                    plaatsen
                WHERE 
                    plaatsId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getPlaceByZipCode(string $zipCode): array
    {
        $sql = "SELECT
                    plaatsId,
                    postcode,
                    plaats
                FROM
                    plaatsen
                WHERE 
                    postcode = :zipCode";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["zipCode" => $zipCode]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function createAddress(string $straat, string $huisNummer, string $bus, int $plaatsId): int
    {
        $sql = "INSERT INTO
                    adressen (straat, huisNummer, bus, plaatsId, actief) 
                VALUES 
                    (:straat, :huisNummer, :bus, :plaatsId, :actief)";
        try {
            $this->connect();
            $id =  $this->create(
                $sql,
                [
                    "straat" => $straat,
                    "huisNummer" => $huisNummer,
                    "bus" => $bus,
                    "plaatsId" => $plaatsId,
                    "actief" => 1
                ]
            );
            $this->disconnect();
            return $id;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
