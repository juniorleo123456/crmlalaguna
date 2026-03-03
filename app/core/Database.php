<?php
// config/config.php

// Constantes de configuración (cambia en producción)
define('DB_HOST', 'localhost');
define('DB_NAME', 'crmlalaguna_v1');
define('DB_USER', 'root');
define('DB_PASS', '');

// Entorno (dev | prod) - útil para cambiar comportamiento
define('APP_ENV', 'dev');

// Función singleton para conexión PDO
function getDBConnection(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            ];

            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (APP_ENV === 'dev') {
                die("Error de conexión a BD: " . $e->getMessage());
            } else {
                // En prod: loguea y muestra error genérico
                error_log("PDO Error: " . $e->getMessage());
                die("Error interno del sistema. Contacta al administrador.");
            }
        }
    }

    return $pdo;
}