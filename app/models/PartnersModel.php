<?php

// app/models/PartnersModel.php

class PartnersModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los socios con información del usuario
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT p.*, 
                   u.name, u.email, u.phone, u.status AS user_status,
                   creator.name AS created_by_name
            FROM partners p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN users creator ON p.created_by = creator.id
            ORDER BY u.name ASC
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un socio por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('
            SELECT p.*, 
                   u.name, u.email, u.phone, u.status AS user_status,
                   creator.name AS created_by_name
            FROM partners p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN users creator ON p.created_by = creator.id
            WHERE p.id = ?
        ');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo socio
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO partners 
            (user_id, commission_rate, commission_type, status, notes, created_by, created_at)
            VALUES (?, ?, ?, 'active', ?, ?, NOW())
        ");
        $stmt->execute([
            $data['user_id'],
            $data['commission_rate'] ?? 0,
            $data['commission_type'] ?? 'percent',
            $data['notes']           ?? null,
            $_SESSION['user_id']     ?? null
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un socio
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE partners SET
                commission_rate = ?,
                commission_type = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?
        ');

        return $stmt->execute([
            $data['commission_rate'] ?? 0,
            $data['commission_type'] ?? 'percent',
            $data['notes']           ?? null,
            $id
        ]);
    }

    /**
    * Cambia el estado activo/inactivo del socio
     * @param int    $id        ID del socio
     * @param string $newStatus 'active' o 'inactive'
     */
    public function toggleStatus(int $id, string $newStatus): bool
    {
        if (!in_array($newStatus, ['active', 'inactive'])) {
            return false;
        }

        $stmt = $this->pdo->prepare('
            UPDATE partners 
            SET status = ? 
            WHERE id = ?
        ');

        return $stmt->execute([$newStatus, $id]);
    }
    /**
     * Cuenta total de socios activos
     */
    public function countTotal(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM partners WHERE status = 'active'");

        return (int) $stmt->fetchColumn();
    }
}
