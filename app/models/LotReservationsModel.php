<?php
// app/models/LotReservationsModel.php

class LotReservationsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT lr.*, 
                   l.lot_number, l.block_id, b.name AS block_name, p.title AS project_title,
                   u.name AS client_name, u.email AS client_email,
                   lr.status AS reservation_status
            FROM lot_reservations lr
            LEFT JOIN lots l ON lr.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            LEFT JOIN clients c ON lr.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY lr.reservation_date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT lr.*, 
                   l.lot_number, l.block_id, b.name AS block_name, p.title AS project_title,
                   u.name AS client_name, u.email AS client_email
            FROM lot_reservations lr
            LEFT JOIN lots l ON lr.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            LEFT JOIN clients c ON lr.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE lr.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO lot_reservations 
            (lot_id, client_id, reservation_date, expiration_date, amount, status, 
             applied_to_sale, notes, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, 'activa', 0, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['lot_id'],
            $data['client_id'],
            $data['reservation_date'] ?? date('Y-m-d H:i:s'),
            $data['expiration_date'] ?? date('Y-m-d H:i:s', strtotime('+30 days')),
            $data['amount'] ?? 300.00,
            $data['notes'] ?? null,
            $_SESSION['user_id'] ?? null
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    // Update y cancelar los agregamos en la siguiente iteración
}
