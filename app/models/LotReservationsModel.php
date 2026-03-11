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

    /**
 * Cancela una reserva y libera el lote
 * @param int $id ID de la reserva
 * @param string $reason Motivo opcional
 * @return bool
 */
public function cancel(int $id, string $reason = ''): bool
{
    $this->pdo->beginTransaction();

    try {
        // 1. Obtener la reserva y su lote
        $stmt = $this->pdo->prepare("
            SELECT lr.lot_id, lr.status
            FROM lot_reservations lr
            WHERE lr.id = ?
        ");
        $stmt->execute([$id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            throw new Exception("Reserva no encontrada");
        }

        if ($reservation['status'] === 'cancelada') {
            throw new Exception("La reserva ya está cancelada");
        }

        // 2. Marcar reserva como cancelada
        $stmt = $this->pdo->prepare("
            UPDATE lot_reservations 
            SET status = 'cancelada',
                notes = CONCAT(IFNULL(notes, ''), '\nCancelada: ', ?),
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$reason ?: 'Cancelación manual', $id]);

        // 3. Liberar el lote (volver a disponible si estaba reservado por esta reserva)
        $stmt = $this->pdo->prepare("
            UPDATE lots 
            SET status = 'disponible',
                updated_at = NOW()
            WHERE id = ? AND status = 'reservado'
        ");
        $stmt->execute([$reservation['lot_id']]);

        // 4. Registrar en historial (opcional pero recomendado)
        $stmt = $this->pdo->prepare("
            INSERT INTO lot_status_history 
            (lot_id, old_status, new_status, reason, changed_by, created_at)
            VALUES (?, 'reservado', 'disponible', ?, ?, NOW())
        ");
        $stmt->execute([
            $reservation['lot_id'],
            "Reserva cancelada (ID {$id}): {$reason}",
            $_SESSION['user_id'] ?? null
        ]);

        $this->pdo->commit();
        return true;
    } catch (Exception $e) {
        $this->pdo->rollBack();
        error_log("Error al cancelar reserva ID {$id}: " . $e->getMessage());
        return false;
    }
}

/**
 * Expira automáticamente todas las reservas que ya pasaron su fecha límite
 * @return int Cantidad de reservas expiradas
 */
public function expireOldReservations(): int
{
    $stmt = $this->pdo->prepare("
        UPDATE lot_reservations 
        SET status = 'expirada'
        WHERE status = 'activa'
          AND expiration_date < NOW()
    ");
    $stmt->execute();
    $expiredCount = $stmt->rowCount();

    if ($expiredCount > 0) {
        // Liberar los lotes asociados
        $this->pdo->exec("
            UPDATE lots l
            INNER JOIN lot_reservations lr ON l.id = lr.lot_id
            SET l.status = 'disponible',
                l.updated_at = NOW()
            WHERE lr.status = 'expirada'
              AND l.status = 'reservado'
        ");
    }

    return $expiredCount;
}

    // Update los agregamos en la siguiente iteración
}
