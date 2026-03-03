<?php
// app/controllers/AuthController.php

class AuthController extends Controller
{

    public function showLogin()
    {
        // Si ya está logueado → redirigir al dashboard (evita ver login con sidebar)
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $this->redirect('dashboard');
    }

    $csrf_token = $this->generateCsrfToken();

    $this->render('auth/login', [
        'csrf_token' => $csrf_token,
        'title'      => 'Iniciar Sesión - CRM La Laguna'
    ]);
    }

    public function processLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->setFlash('warning', 'Método no permitido.');
        $this->redirect('login');
    }

    $rateLimiter = new RateLimiter(getDBConnection());
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    // Verificar bloqueo por IP
    if ($rateLimiter->isBlocked($ip, 'login')) {
        $remaining = $rateLimiter->getRemainingBlockTime($ip, 'login');
        $minutes = ceil($remaining / 60);
        $message = $remaining > 0 
            ? "Demasiados intentos fallidos. Espera {$minutes} minuto" . ($minutes > 1 ? 's' : '') . "."
            : "Demasiados intentos fallidos. Intenta más tarde.";

        $this->setFlash('danger', $message);
        $this->redirect('login');
    }

    // Validar CSRF
    $token = $_POST['csrf_token'] ?? '';
    if (!$this->validateCsrfToken($token)) {
        $rateLimiter->recordAttempt($ip, 'login');
        $this->setFlash('danger', 'Error de validación de seguridad. Intenta de nuevo.');
        $this->redirect('login');
    }

    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $rateLimiter->recordAttempt($ip, 'login');
        $this->setFlash('danger', 'Por favor complete todos los campos.');
        $this->redirect('login');
    }

    // Autenticación real con BD
    $userModel = new User(getDBConnection());
    $user = $userModel->authenticate($email, $password);

    if ($user === false) {
        $rateLimiter->recordAttempt($ip, 'login');
        $this->setFlash('danger', 'Correo o contraseña incorrectos.');
        $this->redirect('login');
    }

    // Verificar que el usuario esté activo
    if ($user['status'] !== 'active') {
        $rateLimiter->recordAttempt($ip, 'login');
        $this->setFlash('danger', 'Tu cuenta no está activa. Contacta al administrador.');
        $this->redirect('login');
    }

    // ¡Éxito! Crear sesión
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['role']      = $user['role'];
    $_SESSION['name']      = $user['name'];
    $_SESSION['logged_in'] = true;

    session_regenerate_id(true);

    // Registrar sesión en BD (y cerrar sesiones anteriores)
    $sessionManager = new SessionManager(getDBConnection());
    $sessionManager->registerSession($user['id'], session_id());

    // Redirección fuerte + anti-caché
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $this->setFlash('success', '¡Bienvenido, ' . htmlspecialchars($user['name']) . '!');
    $this->redirect('dashboard');
}
    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
