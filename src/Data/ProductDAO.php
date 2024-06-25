<?php

declare(strict_types=1);
//src/Data/ProductDAO.php
namespace Webshop\Data;

use PDOException;
use Webshop\Data\DBConnection;

class ProductDAO extends DBConnection
{
    public function getProductById(int $id): array
    {
        $sql = "SELECT 
                    artikelId,
                    ean, 
                    naam, 
                    beschrijving, 
                    prijs, 
                    gewichtInGram,
                    bestelpeil,
                    voorraad,
                    minimumVoorraad,
                    maximumVoorraad,
                    levertijd,
                    aantalBesteldLeverancier,
                    maxAantalInMagazijnPlaats,
                    leveranciersId
                FROM 
                    artikelen 
                WHERE 
                    artikelId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getAllProducts(): array
    {
        $sql = "SELECT 
                    artikelId,
                    ean, 
                    naam, 
                    beschrijving, 
                    prijs, 
                    gewichtInGram,
                    bestelpeil,
                    voorraad,
                    minimumVoorraad,
                    maximumVoorraad,
                    levertijd,
                    aantalBesteldLeverancier,
                    maxAantalInMagazijnPlaats,
                    leveranciersId
                FROM 
                    artikelen";
        try {
            $this->connect();
            $result = $this->readAll($sql);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getAllProductsByCategoryId(int $id): array
    {
        $sql = "SELECT
                    artikelId
                FROM
                    artikelcategorieen
                WHERE
                    categorieId = :id";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function productsBySearch(string $search): array
    {
        $sql = "SELECT DISTINCT
                    artikelId
                FROM
                    artikelen
                WHERE
                    artikelId LIKE :search
                    OR ean LIKE :search
                    OR naam LIKE :search
                    OR beschrijving LIKE :search
                    OR artikelId = :search
                    OR ean = :search
                    OR naam = :search
                    OR beschrijving = :search";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["search" => "%$search%"]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
