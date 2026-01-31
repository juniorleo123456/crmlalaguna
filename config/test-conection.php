<?php
// test_connection.php - Prueba simple de conexión PDO

require_once 'config.php';  // Incluye config

try {
    $pdo = getDBConnection();  // Obtiene conexión

    // Query de prueba: Selecciona menús (datos de ejemplo que insertamos)
    $stmt = $pdo->prepare("SELECT id, label, url FROM menus ORDER BY `order` ASC");
    $stmt->execute();
    $menus = $stmt->fetchAll();

    echo "<h1>Conexión exitosa!</h1>";
    echo "<p>Menús cargados desde BD:</p>";
    echo "<ul>";
    foreach ($menus as $menu) {
        echo "<li>ID: {$menu['id']} - {$menu['label']} ({$menu['url']})</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
