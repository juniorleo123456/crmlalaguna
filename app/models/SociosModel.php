<?php

// app/models/SociosModel.php

class SociosModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los socios con información del usuario y partner
     */

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
        SELECT 
            s.id,
            s.user_id,
            s.nombre_empresa,
            s.direccion,
            s.ciudad,
            s.telefono_extra,
            s.banco,
            s.cuenta_bancaria,
            s.tipo_cuenta,
            s.notes,
            s.status,
            s.created_at,
            u.name,
            u.email,
            u.phone,
            COALESCE(p.commission_rate, 0) AS commission_rate,
            COALESCE(p.commission_type, 'percent') AS commission_type
        FROM socios s
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN partners p ON s.partner_id = p.id
        ORDER BY u.name ASC
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un socio por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('
            SELECT s.*, 
                   u.name, u.email, u.phone, u.status AS user_status,
                   p.commission_rate, p.commission_type,
                   creator.name AS created_by_name
            FROM socios s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN partners p ON s.partner_id = p.id
            LEFT JOIN users creator ON s.created_by = creator.id
            WHERE s.id = ?
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
            INSERT INTO socios 
            (user_id, partner_id, nombre_empresa, direccion, ciudad, telefono_extra,
             banco, cuenta_bancaria, tipo_cuenta, notas, status, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, NOW())
        ");
        $stmt->execute([
            $data['user_id'],
            $data['partner_id']      ?? null,
            $data['nombre_empresa']  ?? null,
            $data['direccion']       ?? null,
            $data['ciudad']          ?? null,
            $data['telefono_extra']  ?? null,
            $data['banco']           ?? null,
            $data['cuenta_bancaria'] ?? null,
            $data['tipo_cuenta']     ?? null,
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
            UPDATE socios SET
                nombre_empresa = ?,
                direccion = ?,
                ciudad = ?,
                telefono_extra = ?,
                banco = ?,
                cuenta_bancaria = ?,
                tipo_cuenta = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?
        ');

        return $stmt->execute([
            $data['nombre_empresa']  ?? null,
            $data['direccion']       ?? null,
            $data['ciudad']          ?? null,
            $data['telefono_extra']  ?? null,
            $data['banco']           ?? null,
            $data['cuenta_bancaria'] ?? null,
            $data['tipo_cuenta']     ?? null,
            $data['notes']           ?? null,
            $id
        ]);
    }

    /**
     * Cambia el estado del socio (activo/inactivo)
     */
    public function toggleStatus(int $id, string $newStatus): bool
    {
        if (!in_array($newStatus, ['active', 'inactive'])) {
            return false;
        }

        $stmt = $this->pdo->prepare('
            UPDATE socios SET status = ? WHERE id = ?
        ');

        return $stmt->execute([$newStatus, $id]);
    }
}
