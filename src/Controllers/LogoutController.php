<?php
//src/Controllers/LoginController.php

declare(strict_types=1);

namespace Webshop\Controllers;

use Webshop\Services\{AuthenticationService, RedirectService};

class LogoutController
{
    public function logout()
    {
        AuthenticationService::logout();
        RedirectService::redirectTo("/");
    }
}
