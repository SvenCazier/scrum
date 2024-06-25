<?php

declare(strict_types=1);
//src/Data/OrderDao.php
namespace Webshop\Data;

use PDOException;
use Webshop\Data\DBConnection;

class OrderDao extends DBConnection
{
    public function createOrder(array $data): int
    {
        $sql = "INSERT INTO
                    bestellingen 
                    (
                        besteldatum, 
                        klantId, 
                        betaald, 
                        betaalwijzeId, 
                        bestellingsStatusId, 
                        actiecodeGebruikt, 
                        bedrijfsnaam, 
                        btwNummer, 
                        voornaam, 
                        familienaam, 
                        facturatieAdresId, 
                        leveringsAdresId
                    ) 
                VALUES
                    (
                        :besteldatum, 
                        :klantId, 
                        :betaald,  
                        :betaalwijzeId, 
                        :bestellingsStatusId, 
                        :actiecodeGebruikt, 
                        :bedrijfsnaam, 
                        :btwNummer, 
                        :voornaam, 
                        :familienaam, 
                        :facturatieAdresId, 
                        :leveringsAdresId
                    ) 
                ";
        try {
            return $this->create($sql, $data);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function createOrderLine(array $data): int
    {
        $sql = "INSERT INTO
                    bestellijnen 
                    (
                        bestelId, 
                        artikelId, 
                        aantalBesteld
                    ) 
                VALUES
                    (
                        :bestelId, 
                        :artikelId, 
                        :aantalBesteld
                    ) 
                ";
        try {
            return $this->create($sql, $data);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getOrderById(int $id): array
    {

        $sql = "SELECT 
                    bestelId, 
                    besteldatum,
                    klantId, 
                    betaald,
                    betalingscode, 
                    betaalwijzeId,
                    annulatie,
                    annulatiedatum,
                    terugbetalingscode,
                    bestellingsStatusId,
                    actieCodeGebruikt,
                    bedrijfsnaam,
                    btwNummer,
                    voornaam,
                    familieNaam,
                    facturatieAdresId,
                    leveringsAdresId
                FROM
                    bestellingen
                WHERE
                    bestelId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getAllOrdersFromCustomer(int $id): array
    {
        $sql = "SELECT 
                    bestelId, 
                    besteldatum,
                    klantId, 
                    betaald,
                    betalingscode, 
                    betaalwijzeId,
                    annulatie,
                    annulatiedatum,
                    terugbetalingscode,
                    bestellingsStatusId,
                    actieCodeGebruikt,
                    bedrijfsnaam,
                    btwNummer,
                    voornaam,
                    familieNaam,
                    facturatieAdresId,
                    leveringsAdresId
                FROM 
                    bestellingen 
                WHERE 
                    klantId = :id";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getOrderLinesByOrderLineId(int $id): array
    {
        $sql = "SELECT
                   bestellijnId,
                   bestelId,
                   artikelId,
                   aantalBesteld,
                   aantalGeannuleerd
                FROM
                   bestellijnen
                WHERE
                   bestellijnId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getAllOrderLinesByOrderId(int $id): array
    {
        $sql = "SELECT
                   bestellijnId,
                   bestelId,
                   artikelId,
                   aantalBesteld,
                   aantalGeannuleerd
                FROM
                   bestellijnen
                WHERE
                   bestelId = :id";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getOrderStatusById(int $id): array
    {
        $sql = "SELECT
                    bestellingsStatusId,
                    naam
                FROM
                    bestellingsstatussen
                WHERE
                    bestellingsStatusId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getPaymentMethodById(int $id): array
    {

        $sql = "SELECT
                    betaalwijzeId,
                    naam
                FROM
                    betaalwijzes
                WHERE
                    betaalwijzeId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getPaymentMethods(): array
    {
        $sql = "SELECT
                    betaalwijzeId,
                    naam
                FROM 
                    betaalwijzes";
        try {
            $this->connect();
            $result = $this->readAll($sql);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
