<?php
// public/index.php - Punto de entrada único

session_start();
require_once __DIR__ . '/../config/config.php';

// =============================================
// 1. Obtener información de la petición
// =============================================
$requestUri   = parse_url($_SERVER['REQUEST_URI'],   PHP_URL_PATH);
$scriptName   = $_SERVER['SCRIPT_NAME'];               // ej: /crmlalaguna1.0/public/index.php

// =============================================
// 2. Calcular el prefijo base de forma dinámica
// =============================================
$basePath = dirname($scriptName);  // quita /index.php → /crmlalaguna1.0/public

// Normalizar (quitar slash final si existe)
$basePath = rtrim($basePath, '/');

// =============================================
// 3. Extraer la ruta real solicitada (quitando el basePath)
// =============================================
$route = $requestUri;

// Si la ruta empieza exactamente con el basePath, quitamos ese prefijo
if (str_starts_with($route, $basePath)) {
    $route = substr($route, strlen($basePath));
}

// Limpiar slashes iniciales y finales
$route = trim($route, '/');

// Si quedó vacío → es la página principal
if ($route === '') {
    $route = 'home';
}

// =============================================
// Router
// =============================================
$routes = [
    'home'       => ['controller' => 'HomeController', 'action' => 'index'],
    'login'      => ['controller' => 'AuthController', 'action' => 'showLogin'],
    'dashboard'  => ['controller' => 'DashboardController', 'action' => 'index'],
    'lots/list'  => ['controller' => 'LotsController', 'action' => 'list'],  // ejemplo con sub-ruta
];

// Procesar ruta
if (isset($routes[$route])) {
    $ctrlName = $routes[$route]['controller'];
    $action   = $routes[$route]['action'];

    $ctrlFile = __DIR__ . '/../app/controllers/' . $ctrlName . '.php';

    if (file_exists($ctrlFile)) {
        require_once $ctrlFile;
        $controller = new $ctrlName();
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            die("La acción <strong>$action</strong> no existe en $ctrlName");
        }
    } else {
        die("Controlador <strong>$ctrlName</strong> no encontrado");
    }
} else {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<p>La ruta solicitada '<strong>$route</strong>' no está definida.</p>";
    exit;
}
