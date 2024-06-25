<?php
//src/Controllers/LoginController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Twig\Environment;
use Webshop\Services\{AuthenticationService, ErrorService, LoginService, RegistrationService, RedirectService, RequestService, SessionService, ValidationService};

class LoginController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        if (AuthenticationService::isAuthenticated()) {
            // already logged in, get out of here
            RedirectService::redirectTo("/");
        }
        echo $this->twig->render(
            "loginTemplate.twig",
            array(
                "page" => [
                    "title" => "",
                    "description" => "",
                ],
                "errors" => ErrorService::getErrors(),
                "referer" => RequestService::getKey("ref"),
                "registrationData" => SessionService::getSession("registrationData")
            )
        );
    }

    public function login()
    {
        $referer = RequestService::getKey("ref");
        if (RequestService::isPost()) {
            $validatedInputs = ValidationService::validateInputs(
                RequestService::postArray(),
                [
                    "login_email" => [ValidationService::NOT_EMPTY, ValidationService::EMAIL],
                    "login_password" => [ValidationService::NOT_EMPTY, ValidationService::PASSWORD]
                ]
            );
            if (!is_null($validatedInputs)) {
                $loginService = new LoginService();
                $loginService->login($validatedInputs["login_email"], $validatedInputs["login_password"]);
                if (AuthenticationService::isAuthenticated()) {
                    // login successful
                    // clear guest user if exists
                    SessionService::clearSessionVariable("guestUser");
                    //redirect
                    RedirectService::redirectTo("/" . $referer);
                }
                // login failed (invalid user credentials or db error), redirect back to login page and display errors
                RedirectService::redirectTo("/login" . ($referer ? "?ref=" . $referer : ""));
            }
            // inputs are invalid, redirect back to login page and display errors
            RedirectService::redirectTo("/login" . ($referer ? "?ref=" . $referer : ""));
        }
        // no post was done, how did you get here? redirect back to safety
        RedirectService::redirectTo("/");
    }

    public function register()
    {
        $referer = RequestService::getKey("ref");
        if (RequestService::isPost()) {
            $registerForm = RequestService::postArray();
            // Base validation array for all cases
            $validationArray = [
                "first_name" => [ValidationService::NOT_EMPTY, ValidationService::TEXT],
                "last_name" => [ValidationService::NOT_EMPTY, ValidationService::TEXT],
                "invoice_street" => [ValidationService::NOT_EMPTY, ValidationService::TEXT],
                "invoice_house_number" => [ValidationService::NOT_EMPTY, ValidationService::HOUSE_NUMBER],
                "invoice_zip_code" => [ValidationService::NOT_EMPTY, ValidationService::INTEGER], // vervangen door datalist met lijst van "places"?
                "invoice_location" => [ValidationService::NOT_EMPTY, ValidationService::TEXT], // vervangen door datalist met lijst van "places"?
                "delivery_street" => [ValidationService::NOT_EMPTY, ValidationService::TEXT],
                "delivery_house_number" => [ValidationService::NOT_EMPTY, ValidationService::HOUSE_NUMBER],
                "delivery_zip_code" => [ValidationService::NOT_EMPTY, ValidationService::INTEGER], // vervangen door datalist met lijst van "places"?
                "delivery_location" => [ValidationService::NOT_EMPTY, ValidationService::TEXT], // vervangen door datalist met lijst van "places"?
                "register_email" => [ValidationService::NOT_EMPTY, ValidationService::EMAIL]
            ];
            // If person_type === "legal_person" add company details validation
            if ($registerForm["person_type"] === "legal_person") {
                // Legal person validations
                $validationArray["company_name"] = [ValidationService::NOT_EMPTY, ValidationService::TEXT];
                $validationArray["vat_number"] = [ValidationService::NOT_EMPTY, ValidationService::VAT_NUMBER];
                $validationArray["company_role"] = [ValidationService::NOT_EMPTY, ValidationService::TEXT];
            }

            // Only validate passwords if create_account is checked
            $isGuestUser = true;
            if (isset($registerForm["create_account"]) && $registerForm["create_account"] === "on") {
                $validationArray["register_password"] = [ValidationService::NOT_EMPTY, ValidationService::PASSWORD];
                $validationArray["confirmation_password"] = [ValidationService::NOT_EMPTY, ValidationService::PASSWORD];
                $isGuestUser = false; // if passwords provided user want to register
            }

            // Validate inputs
            $validatedInputs = ValidationService::validateInputs(
                $registerForm,
                $validationArray
            );

            // If not guest user and passwords are not equal or input validation failed, send back to form, display errors with each input (using errors.inputname), else continue
            if ((!$isGuestUser && !ValidationService::passwordsEqual($registerForm["register_password"], $registerForm["confirmation_password"])) || is_null($validatedInputs)) {
                SessionService::setSession("registrationData", $registerForm); // set data from registration form in session to fill out registration form with original data
                RedirectService::redirectTo("/login" . ($referer ? "?ref=" . $referer : ""));
            }

            // clear previously stored registration data from if form data failed
            SessionService::clearSessionVariable("registrationData");

            $registrationService = new RegistrationService();
            if ($isGuestUser) {
                if ($registrationService->createGuestUser($validatedInputs)) {
                    RedirectService::redirectTo("/" . $referer);
                }
                SessionService::setSession("registrationData", $registerForm);
                RedirectService::redirectTo("/login" . ($referer ? "?ref=" . $referer : ""));
            } else {
                $registrationService->registerUser($validatedInputs);
                if (AuthenticationService::isAuthenticated()) {
                    // registration and login successful
                    RedirectService::redirectTo("/" . $referer);
                }
                // Something went wrong during registration, send back, display errors
                SessionService::setSession("registrationData", $registerForm);
                RedirectService::redirectTo("/login" . ($referer ? "?ref=" . $referer : ""));
            }
        }
        // no post was done, how did you get here? redirect back to safety
        RedirectService::redirectTo("/");
    }

    public function registerGuestUserPassword()
    {
        if (RequestService::isPost()) {
            $passwordForm = RequestService::postArray();
            $validationArray = [
                "register_password" => [ValidationService::NOT_EMPTY, ValidationService::PASSWORD],
                "confirmation_password" => [ValidationService::NOT_EMPTY, ValidationService::PASSWORD],
            ];
            $validatedInputs = ValidationService::validateInputs(
                $passwordForm,
                $validationArray
            );
            if ((!ValidationService::passwordsEqual($passwordForm["register_password"], $passwordForm["confirmation_password"])) || is_null($validatedInputs)) {
                RedirectService::redirectTo("/orderconfirmation");
            }
            $guestUser = SessionService::getSession("guestUser");

            if (!is_null($guestUser)) {
                $registrationService = new RegistrationService();
                $registrationService->addPasswordToAccount($guestUser, $passwordForm["register_password"]);
                if (AuthenticationService::isAuthenticated()) {
                    // registration and login successful remove guest user and redirect to home
                    SessionService::clearSessionVariable("guestUser");
                    RedirectService::redirectTo("/");
                }
                RedirectService::redirectTo("/orderconfirmation");
            }
        }
        RedirectService::redirectTo("/");
    }
}
