<?php

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;

$router = new Router();

// enter your rutes


// Checks and validates routes, if they exist, and assigns Controller functions to them
$router->checkRoutes();