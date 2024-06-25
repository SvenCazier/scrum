<?php
//src\Router\Router.php
declare(strict_types=1);

namespace Webshop\Router;

class Router
{
    private array $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function handleRequest(string $uri, string $method, $twig): void
    {
        // Separate the URI path and query string
        $parts = explode('?', $uri);
        $path = $parts[0];
        $query = isset($parts[1]) ? $parts[1] : '';

        foreach ($this->routes[$method] as $regex => $controller) {
            // Check if the path matches the route pattern
            $pattern = "@^" . preg_replace_callback("/\\\:(?<param>[a-zA-Z0-9\_\-]+)/", function ($matches) {
                // Use named subpatterns instead of indexed subpatterns
                return "(?P<" . $matches['param'] . ">[a-zA-Z0-9\-\_]+)";
            }, preg_quote($regex)) . "$@D";

            if (preg_match($pattern, $path, $matches)) {
                // Parse the query string into an associative array
                parse_str($query, $queryParams);
                // Merge path parameters and query parameters
                $params = array_merge($matches, $queryParams);
                // Invoke the controller
                $this->invokeController($controller, $twig, $params);
                return;
            }
        }

        // If no route matches, send 404 response
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    private function invokeController(string $controller, $twig, array $params = []): void
    {
        [$controllerClass, $method] = explode('@', $controller);
        $controllerInstance = new $controllerClass($twig);
        $controllerInstance->$method(array_slice($params, 1));
    }
}
