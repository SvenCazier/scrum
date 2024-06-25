<?php
//src/Controllers/TermsAndConditionsController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\AuthenticationService;

class CookiesController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "cookiesTemplate.twig",
            array(
                "page" => [
                    "title" => "Prularia - Cookie Policy",
                    "description" => "",
                ],
                "authenticatedUser" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
