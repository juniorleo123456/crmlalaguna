<?php
// config/config.php  ← mejor ponerlo en carpeta config/

// Constantes de configuración
define('DB_HOST', 'localhost');
define('DB_NAME', 'crmlalaguna_v1');
define('DB_USER', 'root');
define('DB_PASS', '');

// Función única para obtener la conexión PDO (singleton simple)
function getDBConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En desarrollo mostramos, en producción logueamos
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    return $pdo;
}
