<?php
//src/Controllers/PrivacyPolicyController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\AuthenticationService;

class PrivacyPolicyController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "privacyPolicyTemplate.twig",
            array(
                "page" => [
                    "title" => "Prularia - Algemene Voorwaarden",
                    "description" => "",
                ],
                "authenticatedUser" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
