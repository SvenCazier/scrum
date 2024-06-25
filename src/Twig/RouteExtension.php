<?php
//src/Twig/RouteExtension.php

namespace Webshop\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    private $route;

    public function __construct()
    {
        $this->route = $this->route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("route", [$this, "getRoute"]),
        ];
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
