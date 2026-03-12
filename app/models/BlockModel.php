<?php
// app/models/BlockModel.php

class BlockModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todas las manzanas (con nombre del proyecto)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT b.id, b.project_id, b.name, b.description, b.total_lots, 
                   b.min_monthly_payment, b.initial_payment, b.status,
                   p.title AS project_title
            FROM blocks b
            LEFT JOIN projects p ON b.project_id = p.id
            ORDER BY p.title, b.name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una manzana por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT b.*, p.title AS project_title
            FROM blocks b
            LEFT JOIN projects p ON b.project_id = p.id
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function projectExists(int $projectId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM projects WHERE id = ?");
        $stmt->execute([$projectId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Crea una nueva manzana
     */
    public function create(array $data): int
    {

        if (!$this->projectExists($data['project_id'])) {
            throw new InvalidArgumentException("El proyecto con ID {$data['project_id']} no existe.");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO blocks 
            (project_id, name, description, total_lots, min_monthly_payment, initial_payment, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())
        ");
        $stmt->execute([
            $data['project_id'],
            $data['name'],
            $data['description'] ?? null,
            $data['total_lots'] ?? 0,
            $data['min_monthly_payment'] ?? 0,
            $data['initial_payment'] ?? 0
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una manzana
     */
    public function update(int $id, array $data): bool
    {

        if (!$this->projectExists($data['project_id'])) {
            throw new InvalidArgumentException("El proyecto con ID {$data['project_id']} no existe.");
        }

        $stmt = $this->pdo->prepare("
            UPDATE blocks SET
                project_id = ?,
                name = ?,
                description = ?,
                total_lots = ?,
                min_monthly_payment = ?,
                initial_payment = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['project_id'],
            $data['name'],
            $data['description'] ?? null,
            $data['total_lots'] ?? 0,
            $data['min_monthly_payment'] ?? 0,
            $data['initial_payment'] ?? 0,
            $id
        ]);
    }

    /**
     * Cuenta los lote reales asociados a una manzana
     */
    public function countRealLots(int $blockId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM lots WHERE block_id = ?");
        $stmt->execute([$blockId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Obtiene todas las manzanas activas de un proyecto específico
     */

    public function getByProject(int $projectId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT b.*, p.title AS project_title 
            FROM blocks b
            LEFT JOIN projects p ON b.project_id = p.id
            WHERE project_id = ? AND status = 'active'
            ORDER BY name
        ");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cambia el estado activo/inactivo de la manzana
     */
    public function toggleStatus(int $id, string $newStatus): bool
    {
        if (!in_array($newStatus, ['active', 'inactive'])) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE blocks SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $id]); 
    }
}
