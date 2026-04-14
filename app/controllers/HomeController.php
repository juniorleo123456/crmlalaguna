<?php

// app/controllers/HomeController.php

class HomeController
{
    public function index()
    {
        echo '<h1>Bienvenido al Sistema Inmobiliario CRM La Laguna</h1>';
        echo '<p>Estás en la ruta raíz (/home o /)</p>';
        echo "<p><a href='login'>Ir a Login (en construcción)</a></p>";
    }
}
