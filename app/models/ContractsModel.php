<?php
// app/models/ContractsModel.php

class ContractsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los contratos de un cliente (o todos si no se pasa client_id)
     */
    public function getAll(int $clientId = 0): array
    {
        if ($clientId > 0) {
            $stmt = $this->pdo->prepare("
                SELECT 
                    c.*,
                    u.name AS client_name,
                    u.email AS client_email,
                    creator.name AS uploaded_by_name,
                    ls.total_price
                FROM contracts c
                JOIN clients cl ON c.client_id = cl.id
                JOIN users u ON cl.user_id = u.id
                LEFT JOIN users creator ON c.uploaded_by = creator.id
                LEFT JOIN lot_sales ls ON c.lot_sale_id = ls.id
                WHERE c.client_id = ?
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([$clientId]);
        } else {
            $stmt = $this->pdo->query("
                SELECT 
                    c.*,
                    u.name AS client_name,
                    u.email AS client_email,
                    creator.name AS uploaded_by_name,
                    ls.total_price
                FROM contracts c
                JOIN clients cl ON c.client_id = cl.id
                JOIN users u ON cl.user_id = u.id
                LEFT JOIN users creator ON c.uploaded_by = creator.id
                LEFT JOIN lot_sales ls ON c.lot_sale_id = ls.id
                ORDER BY c.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un contrato por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.name AS uploaded_by_name
            FROM contracts c
            LEFT JOIN users u ON c.uploaded_by = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo contrato (subida de archivo)
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO contracts 
            (client_id, lot_sale_id, contract_type, file_path, file_name, description, 
             signed_date, status, uploaded_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $data['client_id'],
            $data['lot_sale_id'] ?? null,
            $data['contract_type'],
            $data['file_path'],
            $data['file_name'],
            $data['description'] ?? null,
            $data['signed_date'] ?? null,
            $data['status'] ?? 'uploaded',
            $_SESSION['user_id'] ?? null
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}