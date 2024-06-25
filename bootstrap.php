<?php
// bootstrap.php

declare(strict_types=1);
session_start();
require_once("vendor/autoload.php");

spl_autoload_register();

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Webshop\Twig\{BasePathExtension, RouteExtension};

$loader = new FilesystemLoader(__DIR__ . "/src/Views");
$twig = new Environment($loader);
$twig->addExtension(new BasePathExtension());
$twig->addExtension(new RouteExtension());
