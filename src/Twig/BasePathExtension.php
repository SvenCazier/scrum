<?php
//src/Twig/BasePathExtension.php

namespace Webshop\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BasePathExtension extends AbstractExtension
{
    private $basePath;

    public function __construct()
    {
        $this->basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), DIRECTORY_SEPARATOR);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("basePath", [$this, "getBasePath"]),
        ];
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
