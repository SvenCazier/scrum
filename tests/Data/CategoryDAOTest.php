<?php
//tests/CategoryDAOTest.php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Webshop\Data\CategoryDAO;

class CategoryDAOTest extends TestCase
{
    public function testGetCategoryByIdWithNoParentCategory(): void
    {
        $expectedResult = [
            "categorieId" => 1,
            "naam" => "Huishouden",
            "hoofdCategorieId" => null,
        ];
        $categoryDAO = new CategoryDAO();
        $actualResult = $categoryDAO->getCategoryById(1);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetCategoryByIdWithParentCategory(): void
    {
        $expectedResult = [
            "categorieId" => 2,
            "naam" => "Schoonmaken",
            "hoofdCategorieId" => 1,
        ];
        $categoryDAO = new CategoryDAO();
        $actualResult = $categoryDAO->getCategoryById(2);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetAllCategories(): void
    {
        $expectedResult = [
            [
                "categorieId" => 1,
                "naam" => "Huishouden",
                "hoofdCategorieId" => null,
            ],
            [
                "categorieId" => 2,
                "naam" => "Schoonmaken",
                "hoofdCategorieId" => 1,
            ],
        ];

        $categoryCount = 30;

        $categoryDAO = new CategoryDAO();
        $actualResult = $categoryDAO->getAllCategories();

        $this->assertCount($categoryCount, $actualResult);
        foreach ($expectedResult as $expectedProduct) {
            $this->assertContains($expectedProduct, $actualResult);
        }
    }
}
