<?php
//src/Controllers/OrderConfirmationController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\{AuthenticationService, ErrorService, RedirectService, SessionService};

class OrderConfirmationController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        $authenticatedUser = AuthenticationService::isAuthenticated();
        $guestUser = SessionService::getSession("guestUser");
        $order = SessionService::getSession("order");
        // veranderen naar order in session, in checkout controller dan order in session steken
        if ((!is_null($authenticatedUser) || !is_null($guestUser)) && $order) {
            // clear the order so that they can't visit the page indefinitely after having placed an order
            SessionService::clearSessionVariable("order");

            echo $this->twig->render(
                "orderConfirmationTemplate.twig",
                array(
                    "page" => [
                        "title" => "",
                        "description" => "",
                    ],
                    "authenticatedUser" => $authenticatedUser,
                    "guestUser" => $guestUser,
                    "errors" => ErrorService::getErrors(),
                )
            );
        } else {
            RedirectService::redirectTo("/");
        }
    }
}
