<?php
//src/Services/AuthenticationService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Entities\User;
use Webshop\Services\SessionService;

class AuthenticationService
{

    public static function login($user): void
    {
        SessionService::setSession("user", $user);
    }

    public static function logout(): void
    {
        SessionService::clearSessionVariable("user");
    }

    public static function isAuthenticated(): ?User
    {
        return SessionService::getSession("user");
    }
}
