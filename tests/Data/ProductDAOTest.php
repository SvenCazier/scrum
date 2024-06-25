<?php
//tests/ProductDAOTest.php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Webshop\Data\ProductDAO;

class ProductDAOTest extends TestCase
{
    public function testGetProductById(): void
    {
        // Define the expected product data
        $expectedProductData = [
            "artikelId" => 1,
            "ean" => "5499999000019",
            "naam" => "emmer 10 l",
            "beschrijving" => "huishoudemmer inhoud 10 l",
            "prijs" => "2.00000",
            "gewichtInGram" => 400,
            "bestelpeil" => 25,
            "voorraad" => 50,
            "minimumVoorraad" => 10,
            "maximumVoorraad" => 100,
            "levertijd" => 1,
            "aantalBesteldLeverancier" => 0,
            "maxAantalInMagazijnPlaats" => 25,
            "leveranciersId" => 5
        ];

        // Create an instance of ProductDAO
        $productDAO = new ProductDAO();

        // Call the getProductById method
        $product = $productDAO->getProductById(1);

        // Assert that the returned product matches the expected data
        $this->assertEquals($expectedProductData, $product);
    }

    public function testGetAllProducts(): void
    {
        // Define the expected product data
        $expectedProducts = [
            [
                "artikelId" => 1,
                "ean" => "5499999000019",
                "naam" => "emmer 10 l",
                "beschrijving" => "huishoudemmer inhoud 10 l",
                "prijs" => "2.00000",
                "gewichtInGram" => 400,
                "bestelpeil" => 25,
                "voorraad" => 50,
                "minimumVoorraad" => 10,
                "maximumVoorraad" => 100,
                "levertijd" => 1,
                "aantalBesteldLeverancier" => 0,
                "maxAantalInMagazijnPlaats" => 25,
                "leveranciersId" => 5
            ],
            [
                "artikelId" => 2,
                "ean" => "5499999000026",
                "naam" => "emmer 12 l",
                "beschrijving" => "huishoudemmer inhoud 12 l",
                "prijs" => "4.00000",
                "gewichtInGram" => 500,
                "bestelpeil" => 25,
                "voorraad" => 50,
                "minimumVoorraad" => 10,
                "maximumVoorraad" => 100,
                "levertijd" => 1,
                "aantalBesteldLeverancier" => 0,
                "maxAantalInMagazijnPlaats" => 25,
                "leveranciersId" => 5
            ],
        ];

        // Set to actual amount of products in the database
        $productCount = 266;

        // Create an instance of ProductDAO
        $productDAO = new ProductDAO();

        // Call the getAllProducts method
        $products = $productDAO->getAllProducts();

        // Assert that the number of returned products matches the number of expected products
        $this->assertCount($productCount, $products);

        // Assert that all expected products are contained in the returned products
        foreach ($expectedProducts as $expectedProduct) {
            $this->assertContains($expectedProduct, $products);
        }
    }
}
