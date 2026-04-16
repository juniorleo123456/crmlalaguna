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
     * Lista todos los socios con datos del usuario
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                s.id,
                s.user_id,
                u.name,
                u.email,
                u.phone,
                s.nombre_empresa,
                s.direccion,
                s.ciudad,
                s.telefono_extra,
                s.banco,
                s.cuenta_bancaria,
                s.tipo_cuenta,
                s.notes,
                s.status,
                s.created_at
            FROM socios s
            JOIN users u ON s.user_id = u.id
            ORDER BY u.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un socio por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.*, u.name, u.email, u.phone
            FROM socios s
            JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo socio (devuelve ID insertado)
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO socios 
            (user_id, nombre_empresa, direccion, ciudad, telefono_extra, 
             banco, cuenta_bancaria, tipo_cuenta, notes, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
        ");

        $stmt->execute([
            $data['user_id'],
            $data['nombre_empresa']  ?? null,
            $data['direccion']       ?? null,
            $data['ciudad']          ?? null,
            $data['telefono_extra']  ?? null,
            $data['banco']           ?? null,
            $data['cuenta_bancaria'] ?? null,
            $data['tipo_cuenta']     ?? null,
            $data['notes']           ?? null
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un socio existente
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE socios SET
                nombre_empresa  = ?,
                direccion       = ?,
                ciudad          = ?,
                telefono_extra  = ?,
                banco           = ?,
                cuenta_bancaria = ?,
                tipo_cuenta     = ?,
                notes           = ?,
                updated_at      = NOW()
            WHERE id = ?
        ");

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
     * Cambia el estado activo/inactivo
     */
    public function toggleStatus(int $id, string $newStatus): bool
    {
        if (!in_array($newStatus, ['active', 'inactive'])) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE socios SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $id]);
    }
}