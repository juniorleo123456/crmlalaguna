<?php
// app/models/CommissionsModel.php

class CommissionsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todas las comisiones pagadas a socios
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                c.*,
                s.nombre_empresa,
                u.name AS socio_name,
                u.email AS socio_email,
                creator.name AS registered_by_name,
                ls.total_price,
                l.lot_number,
                b.name AS block_name
            FROM commissions c
            JOIN socios s ON c.socio_id = s.id
            JOIN users u ON s.user_id = u.id
            LEFT JOIN users creator ON c.registered_by = creator.id
            LEFT JOIN lot_sales ls ON c.lot_sale_id = ls.id
            LEFT JOIN lots l ON ls.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            ORDER BY c.payment_date DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una comisión por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, s.nombre_empresa, u.name AS socio_name
            FROM commissions c
            JOIN socios s ON c.socio_id = s.id
            JOIN users u ON s.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo pago de comisión
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO commissions
            (socio_id, lot_sale_id, amount, payment_date, notes, registered_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $data['socio_id'],
            $data['lot_sale_id'] ?? null,
            $data['amount'],
            $data['payment_date'] ?? date('Y-m-d'),
            $data['notes'] ?? null,
            $_SESSION['user_id'] ?? null
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}