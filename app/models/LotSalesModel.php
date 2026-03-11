<?php
// app/models/LotSalesModel.php

class LotSalesModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todas las ventas (con cliente y lote)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT ls.*, 
                   l.lot_number, l.block_id, l.status AS lot_status,
                   b.name AS block_name, p.title AS project_title,
                   u.name AS client_name, u.email AS client_email
            FROM lot_sales ls
            LEFT JOIN lots l ON ls.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            LEFT JOIN clients c ON ls.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY ls.sale_date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una venta por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT ls.*, 
                   l.lot_number, l.block_id, l.status AS lot_status,
                   b.name AS block_name, p.title AS project_title,
                   u.name AS client_name, u.email AS client_email
            FROM lot_sales ls
            LEFT JOIN lots l ON ls.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            LEFT JOIN clients c ON ls.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE ls.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea una nueva venta (asociación cliente-lote)
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO lot_sales 
            (lot_id, client_id, sale_date, total_price, initial_payment, balance, 
             payment_term, interest_rate, monthly_fixed_payment, monthly_min_payment,
             discount_percent, payment_status, consecutive_missed, total_missed,
             final_payment_deadline, contract_file, notes, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'al_dia', 0, 0, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['lot_id'],
            $data['client_id'],
            $data['sale_date'] ?? date('Y-m-d'),
            $data['total_price'],
            $data['initial_payment'] ?? 0,
            $data['balance'] ?? $data['total_price'],
            $data['payment_term'] ?? 0,
            $data['interest_rate'] ?? 0,
            $data['monthly_fixed_payment'] ?? 0,
            $data['monthly_min_payment'] ?? 0,
            $data['discount_percent'] ?? 0,
            $data['final_payment_deadline'] ?? null,
            $data['contract_file'] ?? null,
            $data['notes'] ?? null,
            $_SESSION['user_id'] ?? null
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una venta/contrato existente
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE lot_sales SET
            lot_id = ?,
            client_id = ?,
            sale_date = ?,
            total_price = ?,
            initial_payment = ?,
            balance = ?,
            payment_term = ?,
            interest_rate = ?,
            monthly_fixed_payment = ?,
            monthly_min_payment = ?,
            discount_percent = ?,
            payment_status = ?,
            consecutive_missed = ?,
            total_missed = ?,
            final_payment_deadline = ?,
            contract_file = ?,
            notes = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
        return $stmt->execute([
            $data['lot_id'],
            $data['client_id'],
            $data['sale_date'],
            $data['total_price'],
            $data['initial_payment'] ?? 0,
            $data['balance'] ?? 0,
            $data['payment_term'] ?? 0,
            $data['interest_rate'] ?? 0,
            $data['monthly_fixed_payment'] ?? 0,
            $data['monthly_min_payment'] ?? 0,
            $data['discount_percent'] ?? 0,
            $data['payment_status'] ?? 'al_dia',
            $data['consecutive_missed'] ?? 0,
            $data['total_missed'] ?? 0,
            $data['final_payment_deadline'] ?? null,
            $data['contract_file'] ?? null,
            $data['notes'] ?? null,
            $id
        ]);
    }

    /**
 * Lista ventas con paginación, filtros y búsqueda
 */
public function getAllFiltered(int $page = 1, int $perPage = 10, array $filters = []): array
{
    $where = [];
    $params = [];

    // Filtro por estado de pago
    if (!empty($filters['payment_status'])) {
        $where[] = "ls.payment_status = ?";
        $params[] = $filters['payment_status'];
    }

    // Filtro por cliente
    if (!empty($filters['client_id'])) {
        $where[] = "ls.client_id = ?";
        $params[] = $filters['client_id'];
    }

    // Búsqueda por número de lote o nombre de cliente
    if (!empty($filters['search'])) {
        $search = '%' . trim($filters['search']) . '%';
        $where[] = "(l.lot_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $offset = ($page - 1) * $perPage;

    $stmt = $this->pdo->prepare("
        SELECT ls.*, 
               l.lot_number, l.block_id, l.status AS lot_status,
               b.name AS block_name, p.title AS project_title,
               u.name AS client_name, u.email AS client_email
        FROM lot_sales ls
        LEFT JOIN lots l ON ls.lot_id = l.id
        LEFT JOIN blocks b ON l.block_id = b.id
        LEFT JOIN projects p ON b.project_id = p.id
        LEFT JOIN clients c ON ls.client_id = c.id
        LEFT JOIN users u ON c.user_id = u.id
        $whereClause
        ORDER BY ls.sale_date DESC
        LIMIT ? OFFSET ?
    ");

    $params[] = $perPage;
    $params[] = $offset;

    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Cuenta total de ventas con los mismos filtros (para paginación)
 */
public function countFiltered(array $filters = []): int
{
    $where = [];
    $params = [];

    if (!empty($filters['payment_status'])) {
        $where[] = "ls.payment_status = ?";
        $params[] = $filters['payment_status'];
    }

    if (!empty($filters['client_id'])) {
        $where[] = "ls.client_id = ?";
        $params[] = $filters['client_id'];
    }

    if (!empty($filters['search'])) {
        $search = '%' . trim($filters['search']) . '%';
        $where[] = "(l.lot_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM lot_sales ls
        LEFT JOIN lots l ON ls.lot_id = l.id
        LEFT JOIN clients c ON ls.client_id = c.id
        LEFT JOIN users u ON c.user_id = u.id
        $whereClause
    ");

    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}
/**
 * Cancela una venta y libera el lote
 * @param int $id ID de la venta
 * @param string $reason Motivo opcional de cancelación
 * @return bool
 */
public function cancel(int $id, string $reason = ''): bool
{
    $this->pdo->beginTransaction();

    try {
        // 1. Obtener la venta y su lote
        $stmt = $this->pdo->prepare("
            SELECT ls.lot_id, ls.payment_status
            FROM lot_sales ls
            WHERE ls.id = ?
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sale) {
            throw new Exception("Venta no encontrada");
        }

        if ($sale['payment_status'] === 'cancelado') {
            throw new Exception("La venta ya está cancelada");
        }

        // 2. Marcar venta como cancelada
        $stmt = $this->pdo->prepare("
            UPDATE lot_sales 
            SET payment_status = 'cancelado',
                canceled_at = NOW(),
                canceled_reason = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$reason, $id]);

        // 3. Liberar el lote (volver a disponible)
        $stmt = $this->pdo->prepare("
            UPDATE lots 
            SET status = 'disponible',
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$sale['lot_id']]);

        // 4. Registrar en historial (opcional pero recomendado)
        $stmt = $this->pdo->prepare("
            INSERT INTO lot_status_history 
            (lot_id, old_status, new_status, reason, changed_by, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $sale['lot_id'],
            'vendido', // o el estado que tenía
            'disponible',
            "Venta cancelada (ID {$id}): {$reason}",
            $_SESSION['user_id'] ?? null
        ]);

        $this->pdo->commit();
        return true;
    } catch (Exception $e) {
        $this->pdo->rollBack();
        error_log("Error al cancelar venta ID {$id}: " . $e->getMessage());
        return false;
    }
}
}
