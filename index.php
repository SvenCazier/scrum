<?php
// index.php
declare(strict_types=1);
require_once("bootstrap.php");
// require_once("playground.php"); 
// **testing purposes**

/**
 * To add a new controller and its corresponding route:
 * 1. Create the controller class in the 'Webshop\Controllers' namespace.
 * 2. Define a method within the controller class to handle the specific functionality (e.g., login or register).
 * 3. Add a new route entry in the $routes array:
 *    - For GET requests: ['GET' => [$basePath . 'route_path' => ControllerClassName::class . "@methodName"]]
 *    - For POST requests: ['POST' => [$basePath . 'route_path' => ControllerClassName::class . "@methodName"]]
 *    Replace 'route_path' with the desired path for the route. (e.g. "/login", "/register")
 *    Replace 'ControllerClassName' with the name of the controller class.
 *    Replace 'methodName' with the name of the method within the controller class that handles the request.
 * 5. If necessary, provide any required parameters in the route path (e.g. "/product/:id").
 *    These parameters will be passed to the controller method as an array.
 * 6. Save the changes and test the new route to verify its functionality.
 */


// Import necessary classes
use Webshop\Router\Router;
use Webshop\Controllers\{ // Import specific controllers to use in the application
    HomeController,
    LoginController,
    LogoutController,
    CategoryController,
    ProductController,
    CheckoutController,
    TermsAndConditionsController,
    PrivacyPolicyController,
    OrderConfirmationController,
    CookiesController,
    SearchResultsController
};

// Define the base path for routes, including the project folder
// This ensures proper routing, especially during development when accessing the application via localhost/projectfolder/
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), DIRECTORY_SEPARATOR);

// Define routes
$routes = [
    "GET" => [ // Define routes for HTTP GET requests
        $basePath . "/" => HomeController::class . "@index", // Route for the home page, invoking the "index" method of the HomeController class
        $basePath . "/login" => LoginController::class . "@index", //Route for the Login page view
        $basePath . "/category" => CategoryController::class . "@index", //Route for the Category page view
        $basePath . "/product/:id" => ProductController::class . "@index", //Route for the Product page view
        $basePath . "/api/product/:id" => ProductController::class . "@api", //Route for the Product page API
        $basePath . "/checkout" => CheckoutController::class . "@index", //Route for the Checkout page view
        $basePath . "/algemenevoorwaarden" => TermsAndConditionsController::class . "@index", //Route for the Terms And Conditions page view
        $basePath . "/privacypolicy" => PrivacyPolicyController::class . "@index", //Route for the Privacy Policy page view
        $basePath . "/orderconfirmation" => OrderConfirmationController::class . "@index", //Route for the Confirmation page view
        $basePath . "/cookies" => CookiesController::class . "@index", //Route for the Checkout page view
        $basePath . "/search" => SearchResultsController::class . "@index", //Route for the Search page view

    ],
    "POST" => [ // Define routes for HTTP POST requests (e.g., form submissions)
        $basePath . "/login" => LoginController::class . "@login", //Route for the Login form submission
        $basePath . "/register" => LoginController::class . "@register", //Route for the Register form submission
        $basePath . "/registerpassword" => LoginController::class . "@registerGuestUserPassword", //Route for the Register form submission
        $basePath . "/logout" => LogoutController::class . "@logout", //Route for the Logout
        $basePath . "/checkout" => CheckoutController::class . "@createOrder", //Route for the Order submission
    ]
];

// Create a router instance
$router = new Router($routes);

// Handle incoming request
$uri = $_SERVER["REQUEST_URI"]; // Get the current URI from the server
$method = $_SERVER["REQUEST_METHOD"]; // Get the request method (GET, POST, etc.)
$router->handleRequest($uri, $method, $twig); // Pass URI, method, and Twig instance to the router
