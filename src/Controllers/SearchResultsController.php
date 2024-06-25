<?php
//src/Controllers/SearchResultsController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\{AuthenticationService, CategoryService, ProductService, ErrorService};

class SearchResultsController{

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(){
        $products = null;
        $productService = new ProductService();
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $products = $productService->productsBySearch($search);
    }

    echo $this->twig->render(
        "searchResultTemplate.twig",
        array(
            "page" => [
                "title" => "",
                "description" => "",
            ],
            "errors" => ErrorService::getErrors(),
            "searchProducts" => $products,
            "authenticatedUser" => AuthenticationService::isAuthenticated(),
            "searchTerm" => $search

        )
    );
}
}