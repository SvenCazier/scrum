<?php
//src/Services/RedirectService.php
declare(strict_types=1);

namespace Webshop\Services;


class RedirectService
{
    public static function redirectTo(string $url): void
    {
        $basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), DIRECTORY_SEPARATOR);
        header("Location: " . $basePath . $url);
        exit();
    }
}
