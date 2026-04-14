<?php

// app/core/RateLimiter.php

class RateLimiter
{
    private PDO $pdo;
    private int $maxAttempts;
    private int $windowSeconds;

    public function __construct(PDO $pdo)
    {
        $this->pdo           = $pdo;
        $this->maxAttempts   = 3;      // Puedes mover a .env o constante más adelante
        $this->windowSeconds = 300;    // 5 minutos
    }

    /**
     * ¿Está bloqueada esta IP para esta acción?
     */
    public function isBlocked(string $ip, string $action = 'login'): bool
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT COUNT(*) as attempts
                FROM rate_limits
                WHERE ip_address = ? 
                  AND action = ? 
                  AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
            ');
            $stmt->execute([$ip, $action, $this->windowSeconds]);
            $attempts = (int) $stmt->fetchColumn();

            return $attempts >= $this->maxAttempts;
        } catch (Exception $e) {
            error_log('RateLimiter error: ' . $e->getMessage());

            return false; // fail-safe: no bloquear si falla BD
        }
    }

    /**
     * Obtener tiempo restante de bloqueo (en segundos)
     */
    public function getRemainingTime(string $ip, string $action = 'login'): int
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT MAX(created_at) as last_attempt
                FROM rate_limits
                WHERE ip_address = ? AND action = ?
            ');
            $stmt->execute([$ip, $action]);
            $last = $stmt->fetchColumn();

            if (!$last) {
                return 0;
            }

            $blockEnd  = strtotime($last) + $this->windowSeconds;
            $remaining = $blockEnd - time();

            return max(0, $remaining);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Registrar intento (exitoso o fallido)
     */
    public function recordAttempt(string $ip, string $action = 'login'): void
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO rate_limits (ip_address, action, created_at)
                VALUES (?, ?, NOW())
            ');
            $stmt->execute([$ip, $action]);

            // Limpieza automática de registros viejos (opcional)
            $this->cleanOldRecords();
        } catch (Exception $e) {
            error_log('RateLimiter record error: ' . $e->getMessage());
        }
    }

    private function cleanOldRecords(): void
    {
        try {
            $this->pdo->exec('
                DELETE FROM rate_limits 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)
            ');
        } catch (Exception $e) {
            // silencioso
        }
    }
    /**
     * Obtiene el tiempo restante de bloqueo desde el PRIMER intento que causó el bloqueo
     */
    public function getRemainingBlockTime(string $ip, string $action = 'login'): int
    {
        try {
            // Buscamos el intento que hizo que llegara al límite
            $stmt = $this->pdo->prepare('
            SELECT created_at
            FROM rate_limits
            WHERE ip_address = ? AND action = ?
            ORDER BY created_at ASC
            LIMIT 1
        ');
            $stmt->execute([$ip, $action]);
            $firstAttempt = $stmt->fetchColumn();

            if (!$firstAttempt) {
                return 0;
            }

            $blockStart = strtotime($firstAttempt);
            $blockEnd   = $blockStart + $this->windowSeconds;
            $remaining  = $blockEnd - time();

            return max(0, $remaining);
        } catch (Exception $e) {
            return 0;
        }
    }
}
