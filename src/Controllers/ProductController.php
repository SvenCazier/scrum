<?php
//src/Controllers/ProductController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\{AuthenticationService, ProductService, ErrorService};

class ProductController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    public function index(array $params)
    {
        $id = $params['id'];
        $productService = new ProductService();
        $productObject = $productService->getProductById((int) $id);
        $categorieId = $productService->getCategorieIdByProductId((int) $id);

        echo $this->twig->render(
            "productTemplate.twig",
            array(
                "page" => [
                    "title" => $productObject->getNaam(),
                    "description" => $productObject->getBeschrijving(),

                ],
                "product" => $productObject,
                "getCategorieIdByProd" => $categorieId[0]["categorieId"],
                "errors" => ErrorService::getErrors(),
                "authenticatedUser" => AuthenticationService::isAuthenticated()
            )
        );
    }

    public function api(array $params)
    {
        $id = $params['id'];
        $productService = new ProductService();
        $productData = $productService->getProductByIdAsArray((int) $id);
        echo json_encode($productData);
    }
}
