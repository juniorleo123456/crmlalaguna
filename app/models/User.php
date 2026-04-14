<?php

// app/models/User.php

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Busca un usuario por email
     * @return array|false Devuelve el usuario o false si no existe
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare('
            SELECT id, name, email, password_hash, role, status, failed_login_attempts
            FROM users 
            WHERE email = ? 
            LIMIT 1
        ');
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    /**
     * Intenta autenticar al usuario
     * @return array|false Devuelve datos del usuario si es válido, false si falla
     */
    public function authenticate(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return false;
        }

        // Verificar contraseña
        if (!password_verify($password, $user['password_hash'])) {
            // Incrementar contador de intentos fallidos (opcional, lo podemos usar con rate limiting después)
            $this->incrementFailedAttempts($user['id']);

            return false;
        }

        // Resetear intentos fallidos si el login es exitoso
        $this->resetFailedAttempts($user['id']);

        // Actualizar última conexión
        $stmt = $this->pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
        $stmt->execute([$user['id']]);

        // Devolver solo los datos necesarios para la sesión
        return [
            'id'     => $user['id'],
            'name'   => $user['name'],
            'role'   => $user['role'],
            'status' => $user['status']
        ];
    }

    private function incrementFailedAttempts(int $userId): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE users 
            SET failed_login_attempts = failed_login_attempts + 1 
            WHERE id = ?
        ');
        $stmt->execute([$userId]);
    }

    private function resetFailedAttempts(int $userId): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE users 
            SET failed_login_attempts = 0 
            WHERE id = ?
        ');
        $stmt->execute([$userId]);
    }
}
