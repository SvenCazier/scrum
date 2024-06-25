<?php
//src/Controllers/HomeController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\AuthenticationService;

class HomeController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "homeTemplate.twig",
            array(
                "page" => [
                    "title" => "",
                    "description" => "",
                ],
                "authenticatedUser" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
