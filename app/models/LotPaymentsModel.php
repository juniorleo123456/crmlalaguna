<?php
// app/models/LotPaymentsModel.php

class LotPaymentsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los pagos con información relacionada (venta/reserva, cliente, lote)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT lp.*,
                   ls.id AS sale_id, ls.sale_date, ls.total_price, ls.payment_status AS sale_status,
                   lr.id AS reservation_id, lr.amount AS reservation_amount,
                   l.lot_number, l.block_id, b.name AS block_name,
                   u.name AS client_name, u.email AS client_email,
                   registrar.name AS registered_by_name
            FROM lot_payments lp
            LEFT JOIN lot_sales ls ON lp.lot_sale_id = ls.id
            LEFT JOIN lot_reservations lr ON lp.lot_reservation_id = lr.id
            LEFT JOIN lots l ON ls.lot_id = l.id OR lr.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN clients c ON ls.client_id = c.id OR lr.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            LEFT JOIN users registrar ON lp.registered_by = registrar.id
            ORDER BY lp.payment_date DESC, lp.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un pago específico por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT lp.*,
                   ls.id AS sale_id, ls.sale_date, ls.total_price,
                   lr.id AS reservation_id, lr.amount AS reservation_amount,
                   l.lot_number, l.block_id, b.name AS block_name,
                   u.name AS client_name, u.email AS client_email,
                   registrar.name AS registered_by_name
            FROM lot_payments lp
            LEFT JOIN lot_sales ls ON lp.lot_sale_id = ls.id
            LEFT JOIN lot_reservations lr ON lp.lot_reservation_id = lr.id
            LEFT JOIN lots l ON ls.lot_id = l.id OR lr.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN clients c ON ls.client_id = c.id OR lr.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            LEFT JOIN users registrar ON lp.registered_by = registrar.id
            WHERE lp.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo pago
     */
    /**
     * Crea un nuevo pago y descuenta del balance si está asociado a una venta
     * @param array $data Datos del pago
     * @return int ID del pago creado o 0 si falla
     */
    public function create(array $data): int
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("
            INSERT INTO lot_payments 
            (lot_sale_id, lot_reservation_id, payment_date, amount, payment_type, 
             payment_method, receipt_number, receipt_file, is_late, late_fee, 
             notes, registered_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

            $stmt->execute([
                $data['lot_sale_id'] ?? null,
                $data['lot_reservation_id'] ?? null,
                $data['payment_date'] ?? date('Y-m-d'),
                $data['amount'],
                $data['payment_type'],
                $data['payment_method'] ?? 'efectivo',
                $data['receipt_number'] ?? null,
                $data['receipt_file'] ?? null,
                $data['is_late'] ?? 0,
                $data['late_fee'] ?? 0.00,
                $data['notes'] ?? null,
                $_SESSION['user_id'] ?? null
            ]);

            $paymentId = (int) $this->pdo->lastInsertId();

            error_log("Intentando crear pago - lot_sale_id: " . var_export($data['lot_sale_id'], true));

            // Descontar del balance si está asociado a una venta
            if (!empty($data['lot_sale_id'])) {
                $stmt = $this->pdo->prepare("
                UPDATE lot_sales 
                SET balance = GREATEST(balance - ?, 0),
                    updated_at = NOW()
                WHERE id = ?
            ");
                $executed = $stmt->execute([$data['amount'], $data['lot_sale_id']]);

                if (!$executed || $stmt->rowCount() === 0) {
                    error_log("No se pudo actualizar balance de venta ID {$data['lot_sale_id']}");
                }
            }

            $this->pdo->commit();
            return $paymentId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error al crear pago: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Actualiza un pago existente
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE lot_payments SET
                lot_sale_id = ?,
                lot_reservation_id = ?,
                payment_date = ?,
                amount = ?,
                payment_type = ?,
                payment_method = ?,
                receipt_number = ?,
                receipt_file = ?,
                is_late = ?,
                late_fee = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['lot_sale_id'] ?? null,
            $data['lot_reservation_id'] ?? null,
            $data['payment_date'],
            $data['amount'],
            $data['payment_type'],
            $data['payment_method'] ?? 'efectivo',
            $data['receipt_number'] ?? null,
            $data['receipt_file'] ?? null,
            $data['is_late'] ?? 0,
            $data['late_fee'] ?? 0.00,
            $data['notes'] ?? null,
            $id
        ]);
    }

    /**
     * Lista solo los pagos que tienen boleta/comprobante subido
     */
    public function getPaymentsWithReceipt(): array
    {
        $stmt = $this->pdo->query("
            SELECT lp.*, 
                   ls.id AS sale_id, l.lot_number, u.name AS client_name
            FROM lot_payments lp
            LEFT JOIN lot_sales ls ON lp.lot_sale_id = ls.id
            LEFT JOIN lots l ON ls.lot_id = l.id
            LEFT JOIN clients c ON ls.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE lp.receipt_file IS NOT NULL
            ORDER BY lp.payment_date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
