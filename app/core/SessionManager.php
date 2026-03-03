<?php
// app/core/SessionManager.php

class SessionManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Registra una nueva sesión activa para el usuario
     */
    public function registerSession(int $userId, string $sessionId): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        try {
            // Opcional: si quieres permitir solo 1 sesión por usuario, descomenta esto
            // $this->destroyUserSessions($userId);

            $stmt = $this->pdo->prepare("
                INSERT INTO active_sessions 
                (user_id, session_id, ip_address, user_agent, created_at, last_activity)
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([$userId, $sessionId, $ip, $userAgent]);
            return true;
        } catch (Exception $e) {
            error_log("Error registrando sesión: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la última actividad de la sesión
     */
    public function updateActivity(string $sessionId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE active_sessions 
                SET last_activity = NOW() 
                WHERE session_id = ?
            ");
            $stmt->execute([$sessionId]);
            return true;
        } catch (Exception $e) {
            error_log("Error actualizando actividad: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si el usuario está activo en la base de datos
     */
    public function isUserActive(int $userId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT status FROM users WHERE id = ? LIMIT 1
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user && $user['status'] === 'active';
        } catch (Exception $e) {
            error_log("Error verificando estado de usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si la sesión es válida y está registrada
     */
    public function isSessionValid(string $sessionId, int $userId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id FROM active_sessions 
                WHERE session_id = ? AND user_id = ? LIMIT 1
            ");
            $stmt->execute([$sessionId, $userId]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            error_log("Error verificando sesión: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Destruye una sesión específica
     */
    public function destroySession(string $sessionId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM active_sessions WHERE session_id = ?
            ");
            $stmt->execute([$sessionId]);
            return true;
        } catch (Exception $e) {
            error_log("Error destruyendo sesión: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Destruye todas las sesiones de un usuario (cierre forzado)
     */
    public function destroyUserSessions(int $userId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM active_sessions WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            return true;
        } catch (Exception $e) {
            error_log("Error destruyendo sesiones de usuario: " . $e->getMessage());
            return false;
        }
    }
}
