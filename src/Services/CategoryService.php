<?php
//src/Services/CategoryService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Services\SessionService;
use Webshop\Data\CategoryDAO;
use Webshop\Entities\Category;

class CategoryService
{

    public function getCategorieTree()
    {
        $result = [];
        $categoryDAO = new CategoryDAO();
        $categories = $categoryDAO->getAllCategories();
        // Find head categories (categories without a parent)
        foreach ($categories as $category) {
            if ($category['hoofdCategorieId'] === NULL) {
                // Recursively get subcategories for the current head category
                $subcategories = $this->getSubcategories($category['categorieId'], $categories);

                // Create a Category object for the current head category
                $currentCategory = new Category(
                    $category['categorieId'],
                    $category['naam'],
                    NULL,
                    $subcategories
                );

                // Add the current head category object to the result
                $result[] = $currentCategory;
            }
        }

        return $result;
    }

    private function getSubcategories($categoryId, $categories)
    {
        $result = [];

        // Find subcategories for the current category
        foreach ($categories as $category) {
            if ($category['hoofdCategorieId'] == $categoryId) {
                // Recursively get subcategories for the current category
                $subcategories = $this->getSubcategories($category['categorieId'], $categories);

                // Create a Category object for the current category
                $currentCategory = new Category(
                    $category['categorieId'],
                    $category['naam'],
                    $category['hoofdCategorieId'],
                    $subcategories
                );

                // Add the current category object to the result
                $result[] = $currentCategory;
            }
        }

        return $result;
    }


    public function getCategoryById(int $id): Category
    {
        $categoryDAO = new CategoryDAO();
        $rawCategory = $categoryDAO->getCategoryById($id);
        $categoryObject = new Category(
            (int) $rawCategory['categorieId'],
            (string) $rawCategory['naam'],
            (int) $rawCategory['hoofdCategorieId']
        );
        return $categoryObject;
    }

    public function getAllCategories(): array
    {
        $categoryDAO = new CategoryDAO();
        $rawCategories = $categoryDAO->getAllCategories();
        $categoryObjects = [];
        foreach ($rawCategories as $rawCategory) {
            $categoryObjects[] = new Category(
                (int) $rawCategory['categorieId'],
                (string) $rawCategory['naam'],
                (int) $rawCategory['hoofdCategorieId']
            );
        }
        return $categoryObjects;
    }

    public function getHeadCategories(): array
    {
        $categoryDAO = new CategoryDAO();
        $rawCategories = $categoryDAO->getHeadCategories();
        $subCategories = [];
        foreach ($rawCategories as $rawCategory) {
            $subCategories[] = $categoryDAO->getSubCategoriesByHeadId((int) $rawCategory['categorieId']);
        }
        $categoryObjects = [];
        foreach ($rawCategories as $rawCategory) {
            $categoryObjects[] = new Category(
                (int) $rawCategory['categorieId'],
                (string) $rawCategory['naam'],
                (int) $rawCategory['hoofdCategorieId'],
                $subCategories
            );
        }
        return $categoryObjects;
    }

    public function getSubCategoriesByHeadId(int $id): array
    {
        $categoryDAO = new CategoryDAO();
        $rawCategories = $categoryDAO->getSubCategoriesByHeadId($id);
        $categoryObjects = [];
        foreach ($rawCategories as $rawCategory) {
            $categoryObjects[] = new Category(
                (int) $rawCategory['categorieId'],
                (string) $rawCategory['naam'],
                (int) $rawCategory['hoofdCategorieId']
            );
        }
        return $categoryObjects;
    }
}

//probably broken service methods after the change in the category entity