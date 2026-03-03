<?php
// app/models/ClientModel.php

class ClientModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los clientes (con paginación básica más adelante)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.id, c.user_id, u.name, u.email, c.phone, c.address, c.city, c.state, c.postal_code, c.company_name, c.status
            FROM clients c
            JOIN users u ON c.user_id = u.id
            ORDER BY u.name ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un cliente por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.name, u.email
            FROM clients c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo cliente (devuelve ID insertado)
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO clients (user_id, phone, address, city, state, postal_code, company_name, tax_id, notes, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
    ");
        $stmt->execute([
            $data['user_id'],
            $data['phone'] ?? null,           // ← AGREGADO AQUÍ
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['postal_code'] ?? null,
            $data['company_name'] ?? null,
            $data['tax_id'] ?? null,
            $data['notes'] ?? null
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un cliente existente
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE clients SET
                address = ?,
                city = ?,
                state = ?,
                postal_code = ?,
                company_name = ?,
                tax_id = ?,
                notes = ?,
                phone = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['postal_code'] ?? null,
            $data['company_name'] ?? null,
            $data['tax_id'] ?? null,
            $data['notes'] ?? null,
            $data['phone'] ?? null,
            $id
        ]);
    }

    /**
     * Cambia el estado activo/inactivo del cliente
     */
    public function toggleStatus(int $id, string $newStatus): bool
    {
        // Primero verificar que el cliente existe
        $stmt = $this->pdo->prepare("SELECT id FROM clients WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            error_log("Intento de toggle status en cliente inexistente: ID $id");
            return false;
        }

        // Validar que el estado sea válido
        if (!in_array($newStatus, ['active', 'inactive'])) {
            error_log("Estado inválido para cliente ID $id: $newStatus");
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE clients SET status = ? WHERE id = ?");
        $result = $stmt->execute([$newStatus, $id]);

        if (!$result) {
            error_log("Error ejecutando toggle status para cliente ID $id: " . implode(", ", $stmt->errorInfo()));
        }

        return $result;
    }
    public function countTotal(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM clients");
        return (int) $stmt->fetchColumn();
    }
}
