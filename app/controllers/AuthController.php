<?php
// app/controllers/AuthController.php

class AuthController
{

    public function showLogin()
    {
        // Vista del formulario
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos.';
            header('Location: /login');
            exit;
        }

        // Simulación temporal (luego usaremos BD)
        $validUsers = [
            'admin@lalaguna.pe' => ['password' => 'password123', 'role' => 'admin', 'name' => 'Administrador'],
            'socio@lalaguna.pe' => ['password' => 'socio456', 'role' => 'socio', 'name' => 'Socio Ejemplo'],
            'cliente@lalaguna.pe' => ['password' => 'cliente789', 'role' => 'cliente', 'name' => 'Cliente Demo'],
        ];

        if (isset($validUsers[$email]) && $password === $validUsers[$email]['password']) {
            $_SESSION['user_id']   = rand(1, 100); // Simulado
            $_SESSION['role']      = $validUsers[$email]['role'];
            $_SESSION['name']      = $validUsers[$email]['name'];
            $_SESSION['logged_in'] = true;

            session_regenerate_id(true);

            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
