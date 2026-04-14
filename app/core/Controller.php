<?php

// app/core/Controller.php

abstract class Controller
{
    /**
     * Datos que estarán disponibles en todas las vistas renderizadas por este controlador
     * @var array
     */
    protected $data = [];

    /**
     * Layout por defecto que se usará para renderizar las vistas
     * @var string
     */
    protected $layout = 'layouts/main';

    /**
     * Renderiza una vista dentro del layout
     *
     * @param string $view Ruta relativa de la vista (ej: 'auth/login')
     * @param array  $data Variables adicionales que se pasarán a la vista
     */
    protected function render(string $view, array $data = []): void
    {
        // Combinar datos del controlador con los que llegan
        $this->data = array_merge($this->data, $data);

        // Extraer variables para usarlas directamente en la vista ($variable en vez de $data['variable'])
        extract($this->data);

        // Capturar salida de la vista
        ob_start();
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(500);
            die("Error: Vista no encontrada → $viewPath");
        }
        require $viewPath;
        $content = ob_get_clean();

        // Cargar el layout y pasar el contenido
        $layoutPath = __DIR__ . '/../views/' . $this->layout . '.php';
        if (!file_exists($layoutPath)) {
            http_response_code(500);
            die("Error: Layout no encontrado → $layoutPath");
        }

        // Variable $content estará disponible en el layout
        require $layoutPath;
    }

    /**
     * Redirige a una ruta relativa usando BASE_URL
     *
     * @param string $route Ej: 'login', 'dashboard', 'lots/list'
     */
    protected function redirect(string $route): never
    {
        header('Location: ' . BASE_URL . ltrim($route, '/'));
        exit;
    }

    /**
     * Establece un mensaje flash (se muestra una sola vez)
     *
     * @param string $type    success | danger | warning | info
     * @param string $message Mensaje a mostrar
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = compact('type', 'message');
    }

    /**
     * Obtiene y elimina el mensaje flash (si existe)
     *
     * @return array|null ['type' => ..., 'message' => ...] o null
     */
    protected function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);

            return $flash;
        }

        return null;
    }

    // ── Protección CSRF básica ───────────────────────────────────────────────

    /**
     * Genera y devuelve un token CSRF (se almacena en sesión)
     *
     * @return string Token CSRF
     */
    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Valida si el token CSRF enviado coincide con el almacenado
     *
     * @param string $token Token recibido del formulario
     */
    protected function validateCsrfToken(string $token): bool
    {
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        $valid = hash_equals($_SESSION['csrf_token'], $token);
        unset($_SESSION['csrf_token']); // Token de un solo uso

        return $valid;
    }
    /**
     * Carga los menús permitidos para el rol actual del usuario
     * @return array Lista de menús con sus propiedades
     */
    protected function getMenusForCurrentUser(): array
    {
        if (!isset($_SESSION['role']) || empty($_SESSION['role'])) {
            return [];
        }

        $role = $_SESSION['role'];

        try {
            $pdo = getDBConnection();  // función que ya tienes en config.php

            $stmt = $pdo->prepare('
            SELECT id, parent_id, label, url, icon, `order`, is_active, roles
            FROM menus
            WHERE JSON_CONTAINS(roles, JSON_QUOTE(:role))
              AND is_active = 1
            ORDER BY `order` ASC, label ASC
        ');

            $stmt->execute(['role' => $role]);
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Opcional: organizar en árbol si tienes submenús
            // Por ahora devolvemos plano (lista simple)
            return $menus;
        } catch (Exception $e) {
            error_log('Error cargando menús: ' . $e->getMessage());

            return [];
        }
    }
    /**
 * Middleware: Requiere autenticación válida y sesión activa
 * Si falla → destruye sesión y redirige al login
 */
    protected function requireLogin(): void
    {
        // 1. ¿Hay sesión iniciada y bandera logged_in?
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
            $this->setFlash('warning', 'Debes iniciar sesión para acceder a esta sección.');
            $this->redirect('login');
        }

        $userId    = (int) $_SESSION['user_id'];
        $sessionId = session_id();

        $sessionManager = new SessionManager(getDBConnection());

        // Si es cliente y su status en clients es inactive → bloquear
        if ($_SESSION['role'] === 'cliente') {
            $stmt = getDBConnection()->prepare('
            SELECT status FROM clients WHERE user_id = ?
        ');
            $stmt->execute([$_SESSION['user_id']]);
            $clientStatus = $stmt->fetchColumn();

            if ($clientStatus === 'inactive') {
                $sessionManager = new SessionManager(getDBConnection());
                $sessionManager->destroySession(session_id());
                session_unset();
                session_destroy();

                $this->setFlash('danger', 'Tu cuenta ha sido desactivada temporalmente por motivos administrativos. Por favor contacta a tu agente o gerencia para regularizar tu situación.');
                $this->redirect('login');
            }
        }

        // 2. ¿El usuario sigue activo en la BD?
        if (!$sessionManager->isUserActive($userId)) {
            $sessionManager->destroySession($sessionId);
            session_unset();
            session_destroy();

            $this->setFlash('danger', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
            $this->redirect('login');
        }

        // 3. ¿La sesión está registrada y es válida?
        if (!$sessionManager->isSessionValid($sessionId, $userId)) {
            session_unset();
            session_destroy();

            $this->setFlash('danger', 'Sesión inválida o expirada. Por favor inicia sesión nuevamente.');
            $this->redirect('login');
        }

        // 4. Actualizar última actividad
        $sessionManager->updateActivity($sessionId);
    }
}
