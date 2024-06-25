<?php
//src/Controllers/CheckoutController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\{AuthenticationService, ErrorService, RegistrationService, OrderService, RedirectService, RequestService, SessionService, ValidationService};

class CheckoutController
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
        if (!is_null($authenticatedUser) || !is_null($guestUser)) { // if there is an authenticated or guest user display the checkout page
            $orderService = new OrderService();

            echo $this->twig->render(
                "checkoutTemplate.twig",
                array(
                    "page" => [
                        "title" => "",
                        "description" => "",
                    ],
                    "authenticatedUser" => $authenticatedUser,
                    "guestUser" => $guestUser,
                    "paymentMethods" => $orderService->getPaymentMethods()
                )
            );
        } else {
            // if you are not logged in or haven't supplied your information as a guest user go do that first
            RedirectService::redirectTo("/login?ref=checkout");
        }
    }

    public function createOrder()
    {
        $user = AuthenticationService::isAuthenticated();
        if (is_null($user)) {
            // no authenticated user create new user out of guest user
            $guestUser = SessionService::getSession("guestUser");
            if (is_null($guestUser)) {
                // if there's no guest user data send back an error
                http_response_code(400);
                echo json_encode(array("success" => false, "message" => "No user data."));
                exit();
            }
            $registrationService = new RegistrationService();
            $user = $registrationService->registerUserWithoutPassword($guestUser);
            SessionService::setSession("guestUser", $user);
        }

        // Retrieve the raw POST data
        $jsonData = file_get_contents("php://input");

        // Check if the data is not empty
        if (!empty($jsonData)) {
            // Decode the JSON data into a PHP associative array
            $shoppingCart = json_decode($jsonData, true);

            // Check if decoding was successful
            if ($shoppingCart !== null) {
                // Access the individual values
                // $validatedShoppingCart = ValidationService::validateInputs(
                //     $shoppingCart,
                //     [
                //         "paymentMethod" => [ValidationService::NOT_EMPTY, ValidationService::INTEGER, ValidationService::NOT_LESS_THAN_OR_EQUAL_TO_ZERO],
                //         "orderLines" => [ValidationService::NOT_EMPTY, ValidationService::IS_ARRAY]
                //     ]
                // );
                $validatedShoppingCart = $shoppingCart;
                if (!is_null($validatedShoppingCart)) {
                    $orderService = new OrderService();
                    if ($orderService->createOrder($user, $shoppingCart)) {
                        SessionService::setSession("order", 1);
                        http_response_code(200);
                        echo json_encode(array("success" => true, "message" => "Bestelling gelukt."));
                        exit();
                    } else {
                        // Handle order creation failure
                        http_response_code(500);
                        echo json_encode(array("success" => false, "message" => ErrorService::getErrors()));
                        exit();
                    }
                } else {
                    // Handle invalid data
                    http_response_code(400);
                    echo json_encode(array("success" => false, "message" => "Invalid data."));
                    exit();
                }
            } else {
                // Handle JSON decoding error
                http_response_code(400);
                echo json_encode(array("success" => false, "message" => "Error decoding JSON data."));
                exit();
            }
        } else {
            // Handle empty POST data
            http_response_code(400);
            echo json_encode(array("success" => false, "message" => "No data received."));
            exit();
        }
    }
}
