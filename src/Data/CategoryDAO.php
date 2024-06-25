<?php

declare(strict_types=1);
//src/Data/CategoryDAO.php
namespace Webshop\Data;

use PDOException;
use Webshop\Data\DBConnection;

class CategoryDAO extends DBConnection
{

    public function getCategoriesbyProductId(int $id): array
    {
        $sql = "SELECT
                    categorieId
                FROM 
                    artikelcategorieen 
                WHERE 
                    artikelId = :id";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getCategoryById(int $id): array
    {
        $sql = "SELECT
                    categorieId,
                    naam,
                    hoofdCategorieId
                FROM 
                    categorieen 
                WHERE 
                    categorieId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getAllCategories(): array
    {
        $sql = "SELECT
                    categorieId,
                    naam,
                    hoofdCategorieId
                FROM 
                    categorieen";
        try {
            $this->connect();
            $result = $this->readAll($sql);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getHeadCategories(): array
    {

        $sql = "SELECT
                    categorieId,
                    naam,
                    hoofdCategorieId
                FROM
                    categorieen
                WHERE
                    hoofdCategorieId IS NULL";
        try {
            $this->connect();
            $result = $this->readAll($sql);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getSubCategoriesByHeadId(int $id): array
    {

        $sql = "SELECT
                    categorieId,
                    naam,
                    hoofdCategorieId
                FROM
                    categorieen
                WHERE
                    hoofdCategorieId = :id";
        try {
            $this->connect();
            $result = $this->readAll($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getCategorieIdByProductId(int $id): array{

        $sql = "SELECT
                    categorieId
                FROM
                    artikelcategorieen
                WHERE
                    productId = :id";
        try {
            $this->connect();
            $result = $this->readOne($sql, ["id" => $id]);
            $this->disconnect();
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
