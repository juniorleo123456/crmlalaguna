<?php

// public/index.php - Punto de entrada único

// Iniciar sesión siempre al principio
session_start();

// Autoloader simple para clases en app/ (controllers, core, models, etc.)
spl_autoload_register(function ($className) {
    // Convertimos namespace o nombre de clase a ruta de archivo
    $className = str_replace('\\', '/', $className); // por si usamos namespaces en el futuro

    // Posibles carpetas donde buscar clases
    $possiblePaths = [
        __DIR__ . '/../app/controllers/' . $className . '.php',
        __DIR__ . '/../app/core/' . $className . '.php',
        __DIR__ . '/../app/models/' . $className . '.php',
        // Puedes agregar más carpetas cuando las crees
    ];

    // Buscar en cada ruta posible
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            require_once $path;

            return; // clase encontrada, salimos
        }
    }

    // Si no se encontró (opcional: puedes lanzar excepción o dejar que PHP falle)
    // error_log("Autoloader: Clase no encontrada: $className");
});

// Cargar configuración
require_once __DIR__ . '/../app/core/Database.php';

// Definir BASE_URL de forma segura
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];                    // /public/index.php o /index.php
$basePath = rtrim(dirname($script), '/\\');             // quita /index.php y maneja Windows

define('BASE_URL', $protocol . $host . $basePath . '/');

// =============================================
// 1. Obtener la ruta solicitada (limpia)
// =============================================
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME']; // ej: /crmlalaguna1.0/public/index.php

// Calcular el prefijo base del proyecto (dinámico)
$basePath = rtrim(dirname($scriptName), '/');

// Quitar el prefijo base de la URI para obtener la ruta real
$route = $requestUri;
if (str_starts_with($route, $basePath)) {
    $route = substr($route, strlen($basePath));
}
$route = trim($route, '/');

// Ruta por defecto
if ($route === '') {
    $route = 'home';
}

// =============================================
// 2. Definir rutas (GET y POST por separado)
// =============================================
$routes = [
    'GET' => [
        ''                             => ['controller' => 'HomeController', 'action' => 'index'], // ← también vale la raíz vacía
        'home'                         => ['controller' => 'HomeController', 'action' => 'index'],
        'login'                        => ['controller' => 'AuthController', 'action' => 'showLogin'],
        'logout'                       => ['controller' => 'AuthController', 'action' => 'logout'],
        'dashboard'                    => ['controller' => 'DashboardController', 'action' => 'index'],
        'clients'                      => ['controller' => 'ClientsController', 'action' => 'index'],
        'clients/create'               => ['controller' => 'ClientsController', 'action' => 'create'],
        'clients/edit/(\d+)'           => ['controller' => 'ClientsController', 'action' => 'edit'],
        'clients/toggle/(\d+)'         => ['controller' => 'ClientsController', 'action' => 'toggleStatus'],
        'projects'                     => ['controller' => 'ProjectsController', 'action' => 'index'],
        'projects/create'              => ['controller' => 'ProjectsController', 'action' => 'create'],
        'projects/edit/(\d+)'          => ['controller' => 'ProjectsController', 'action' => 'edit'],
        'projects/change-status/(\d+)' => ['controller' => 'ProjectsController', 'action' => 'changeStatus'],
        'blocks'                       => ['controller' => 'BlocksController', 'action' => 'index'],
        'blocks/create'                => ['controller' => 'BlocksController', 'action' => 'create'],
        'blocks/edit/(\d+)'            => ['controller' => 'BlocksController', 'action' => 'edit'],
        'blocks/toggle/(\d+)'          => ['controller' => 'BlocksController', 'action' => 'toggleStatus'],
        'blocks/change-status/(\d+)'   => ['controller' => 'BlocksController', 'action' => 'changeStatus'],
        'clients/view/(\d+)'           => ['controller' => 'ClientsController', 'action' => 'view'],
        'client-services/create'       => ['controller' => 'ClientServicesController', 'action' => 'create'],
        'lots'                         => ['controller' => 'LotsController', 'action' => 'index'],
        'lots/create'                  => ['controller' => 'LotsController', 'action' => 'create'],
        'lots/edit/(\d+)'              => ['controller' => 'LotsController', 'action' => 'edit'],
        'lots/toggle/(\d+)'            => ['controller' => 'LotsController', 'action' => 'toggleStatus'],
        'map'                          => ['controller' => 'LotsController', 'action' => 'map'],
        'lot-sales'                    => ['controller' => 'LotSalesController', 'action' => 'index'],
        'lot-sales/create'             => ['controller' => 'LotSalesController', 'action' => 'create'],
        'lot-sales/edit/(\d+)'         => ['controller' => 'LotSalesController', 'action' => 'edit'],
        'lot-reservations'             => ['controller' => 'LotReservationsController', 'action' => 'index'],
        'lot-reservations/create'      => ['controller' => 'LotReservationsController', 'action' => 'create'],
        'lot-payments'                 => ['controller' => 'LotPaymentsController', 'action' => 'index'],
        'lot-payments/create'          => ['controller' => 'LotPaymentsController', 'action' => 'create'],
        'lot-receipts'                 => ['controller' => 'LotPaymentsController', 'action' => 'receipts'],
     // 'lot-reservations/edit/(\d+)' => ['controller' => 'LotReservationsController', 'action' => 'edit'],  // lo agregamos después
    ],

    'POST' => [
        'login'                               => ['controller' => 'AuthController', 'action' => 'processLogin'],
        'clients/create'                      => ['controller' => 'ClientsController', 'action' => 'create'],
        'clients/edit/(\d+)'                  => ['controller' => 'ClientsController', 'action' => 'edit'],
        'clients/toggle/(\d+)'                => ['controller' => 'ClientsController', 'action' => 'toggleStatus'],
        'projects/create'                     => ['controller' => 'ProjectsController', 'action' => 'create'],
        'projects/edit/(\d+)'                 => ['controller' => 'ProjectsController', 'action' => 'edit'],
        'blocks/create'                       => ['controller' => 'BlocksController', 'action' => 'create'],
        'blocks/edit/(\d+)'                   => ['controller' => 'BlocksController', 'action' => 'edit'],
        'client-services/create'              => ['controller' => 'ClientServicesController', 'action' => 'create'],
        'lots/create'                         => ['controller' => 'LotsController', 'action' => 'create'],
        'lots/edit/(\d+)'                     => ['controller' => 'LotsController', 'action' => 'edit'],
        'map_left'                            => (float) ($_POST['map_left'] ?? 0.00),
        'map_top'                             => (float) ($_POST['map_top'] ?? 0.00),
        'map_width'                           => (float) ($_POST['map_width'] ?? 8.00),
        'map_height'                          => (float) ($_POST['map_height'] ?? 8.00),
        'lot-sales/create'                    => ['controller' => 'LotSalesController', 'action' => 'create'],
        'lot-sales/edit/(\d+)'                => ['controller' => 'LotSalesController', 'action' => 'edit'],
        'lot-sales/cancel/(\d+)'              => ['controller' => 'LotSalesController', 'action' => 'cancel'],
        'lot-reservations/create'             => ['controller' => 'LotReservationsController', 'action' => 'create'],
        'lot-reservations/cancel/(\d+)'       => ['controller' => 'LotReservationsController', 'action' => 'cancel'],
        'lot-reservations/confirm-sale/(\d+)' => ['controller' => 'LotReservationsController', 'action' => 'confirmSale'],
        'lot-payments/create'                 => ['controller' => 'LotPaymentsController', 'action' => 'create'],
     // 'lot-reservations/edit/(\d+)' => ['controller' => 'LotReservationsController', 'action' => 'edit'],

        ]
];

// =============================================
// 3. Procesar la ruta
// =============================================
$method     = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route      = trim($requestUri, '/');

$controller = null;
$action     = null;
$params     = [];

foreach ($routes[$method] ?? [] as $pattern => $target) {
    if (preg_match('#^' . str_replace('(\d+)', '(\d+)', $pattern) . '$#', $route, $matches)) {
        $controller = $target['controller'];
        $action     = $target['action'];
        $params     = array_slice($matches, 1); // extrae los parámetros (ej: el ID)
        break;
    }
}

if ($controller && $action) {
    $ctrlFile = __DIR__ . '/../app/controllers/' . $controller . '.php';

    if (file_exists($ctrlFile)) {
        require_once $ctrlFile;

        if (class_exists($controller)) {
            $obj = new $controller();

            if (method_exists($obj, $action)) {
                // Llamar acción pasando parámetros si existen (ej: $id)
                if (!empty($params)) {
                    $obj->$action(...$params);
                } else {
                    $obj->$action();
                }
                exit;
            } else {
                die("La acción <b>$action</b> no existe en $controller");
            }
        } else {
            die("No se encontró la clase <b>$controller</b>");
        }
    } else {
        die("No se encontró el archivo del controlador: <b>$ctrlFile</b>");
    }
}

// Si llegamos aquí → 404
http_response_code(404);
echo '<h1>404 - Página no encontrada</h1>';
echo '<p>Ruta: <b>' . htmlspecialchars($route) . "</b><br>Método: <b>$method</b></p>";
exit;
