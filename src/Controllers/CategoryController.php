<?php
//src/Controllers/HomeController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\{AuthenticationService, CategoryService, ProductService, ErrorService};

class CategoryController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }



    public function index()
    {
        $categoryService = new CategoryService();
        $productService = new ProductService();
        $mainCategorieId = null; // Initialize to null

        if (isset($_GET['categorieId'])) {
            $mainCategorieId = $_GET['categorieId'];
            $getProducts = $productService->getAllProductsByCategoryId((int) $mainCategorieId);
            foreach ($getProducts as $product) {
                $productId = $product->getId();
                $categorieId = $productService->getCategorieIdByProductId($productId);
                $product->setCategorieId($categorieId);
            }
        }

        echo $this->twig->render(
            "categoryTemplate.twig",
            array(
                "page" => [
                    "title" => "",
                    "description" => "",
                ],
                "categories" => $categoryService->getHeadCategories(),
                "getSubByHeadId" => $categoryService->getSubCategoriesByHeadId((int) $mainCategorieId),
                "categorieTree" => $categoryService->getCategorieTree(),
                "getAllCategories" => $categoryService->getAllCategories(),
                "errors" => ErrorService::getErrors(),
                "mainCategorieId" => $mainCategorieId,
                "getProducts" => $productService->getAllProductsByCategoryId((int) $mainCategorieId),
                "authenticatedUser" => AuthenticationService::isAuthenticated()

            )
        );
    }
}
