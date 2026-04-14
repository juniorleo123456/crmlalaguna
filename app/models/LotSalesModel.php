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
        $stmt = $this->pdo->query('
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
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una venta por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('
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
        ');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea una nueva venta (asociación cliente-lote)
     */
    public function create(array $data): int
    {

        $total   = (float) ($data['total_price'] ?? 0);
        $initial = (float) ($data['initial_payment'] ?? 0);

        // 3. Campos nuevos de mora (por defecto si no llegan desde el formulario)
        $dueDay      = (int) ($data['due_day_of_month'] ?? 7);
        $graceDays   = (int) ($data['grace_days'] ?? 7);
        $lateFeeRate = (float) ($data['late_fee_rate'] ?? 10.00);

        $ratePercent = (float) ($data['interest_rate'] ?? 0);
        // normalizar tasa
        $rate   = $ratePercent > 1 ? $ratePercent / 100 : $ratePercent;
        $months = (int) ($data['payment_term'] ?? 0);
        // interés fijo
        $interestTotal = $total * $rate;
        // total final
        $totalWithInterest = $total + $interestTotal;
        // saldo
        $balance = $totalWithInterest - $initial;
        // cuota
        $monthlyApprox = $months > 0 ? $balance / $months : 0;

        $stmt = $this->pdo->prepare("
        INSERT INTO lot_sales 
        (lot_id, client_id, sale_date, total_price, initial_payment, balance, 
         payment_term, interest_rate, monthly_fixed_payment, monthly_min_payment,
         discount_percent, payment_status, consecutive_missed, total_missed,
         final_payment_deadline, contract_file, notes, created_by, created_at,
         total_with_interest, projected_monthly_payment, due_day_of_month, grace_days, late_fee_rate)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'al_dia', 0, 0, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)
    ");
        $stmt->execute([
            $data['lot_id'],
            $data['client_id'],
            $data['sale_date'] ?? date('Y-m-d'),
            $total,
            $initial,
            $balance,
            $months,
            $ratePercent,  // guardamos % original
            $data['monthly_fixed_payment']  ?? 0,
            $data['monthly_min_payment']    ?? 0,
            $data['discount_percent']       ?? 0,
            $data['final_payment_deadline'] ?? null,
            $data['contract_file']          ?? null,
            $data['notes']                  ?? null,
            $_SESSION['user_id']            ?? null,
            $totalWithInterest,
            $monthlyApprox,
            $dueDay,
            $graceDays,
            $lateFeeRate
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una venta/contrato existente
     */
    /**
     * Actualiza una venta/contrato existente
     * @param  int   $id   ID de la venta
     * @param  array $data Datos recibidos del formulario
     * @return bool  True si se actualizó correctamente
     */
    public function update(int $id, array $data): bool
    {
        // 1. Cálculo obligatorio del balance inicial (siempre recalculado)
        $total   = (float) ($data['total_price'] ?? 0);
        $initial = (float) ($data['initial_payment'] ?? 0);

        $dueDay      = (int) ($data['due_day_of_month'] ?? 7);
        $graceDays   = (int) ($data['grace_days'] ?? 7);
        $lateFeeRate = (float) ($data['late_fee_rate'] ?? 10.00);

        // 2. Cálculo de intereses y proyecciones (ya lo tienes bien)
        $ratePercent = (float) ($data['interest_rate'] ?? 0);

        // normalizar tasa
        $rate = $ratePercent > 1 ? $ratePercent / 100 : $ratePercent;

        $months = (int) ($data['payment_term'] ?? 0);

        // interés fijo
        $interestTotal = $total * $rate;

        // total final
        $totalWithInterest = $total + $interestTotal;

        // saldo
        $balance = $totalWithInterest - $initial;

        // cuota
        $monthlyApprox = $months > 0 ? $balance / $months : 0;

        // 3. Preparar y ejecutar la actualización
        $stmt = $this->pdo->prepare('
        UPDATE lot_sales SET
            lot_id = ?,
            client_id = ?,
            sale_date = ?,
            total_price = ?,
            initial_payment = ?,
            balance = ?,                      -- ← siempre calculado
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
            due_day_of_month = ?,
            grace_days = ?,
            late_fee_rate = ?,
            total_with_interest = ?,
            projected_monthly_payment = ?,
            updated_at = NOW()
        WHERE id = ?
    ');

        return $stmt->execute([
            $data['lot_id'],
            $data['client_id'],
            $data['sale_date'],
            $total,
            $initial,
            $balance,                          // ← valor recalculado
            $months,
            $rate * 100,                       // guardamos la tasa en porcentaje
            $data['monthly_fixed_payment']  ?? 0,
            $data['monthly_min_payment']    ?? 0,
            $data['discount_percent']       ?? 0,
            $data['payment_status']         ?? 'al_dia',
            $data['consecutive_missed']     ?? 0,
            $data['total_missed']           ?? 0,
            $data['final_payment_deadline'] ?? null,
            $data['contract_file']          ?? null,
            $data['notes']                  ?? null,
            $dueDay,
            $graceDays,
            $lateFeeRate,
            $totalWithInterest,
            $monthlyApprox,
            $id
        ]);
    }

    /**
     * Lista ventas con paginación, filtros y búsqueda
     */
    public function getAllFiltered(int $page = 1, int $perPage = 10, array $filters = []): array
    {
        $where  = [];
        $params = [];

        // Filtro por estado de pago
        if (!empty($filters['payment_status'])) {
            $where[]  = 'ls.payment_status = ?';
            $params[] = $filters['payment_status'];
        }

        // Filtro por cliente
        if (!empty($filters['client_id'])) {
            $where[]  = 'ls.client_id = ?';
            $params[] = $filters['client_id'];
        }

        // Búsqueda por número de lote o nombre de cliente
        if (!empty($filters['search'])) {
            $search   = '%' . trim($filters['search']) . '%';
            $where[]  = '(l.lot_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)';
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
        $where  = [];
        $params = [];

        if (!empty($filters['payment_status'])) {
            $where[]  = 'ls.payment_status = ?';
            $params[] = $filters['payment_status'];
        }

        if (!empty($filters['client_id'])) {
            $where[]  = 'ls.client_id = ?';
            $params[] = $filters['client_id'];
        }

        if (!empty($filters['search'])) {
            $search   = '%' . trim($filters['search']) . '%';
            $where[]  = '(l.lot_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)';
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
     * @param int    $id     ID de la venta
     * @param string $reason Motivo opcional de cancelación
     */
    public function cancel(int $id, string $reason = ''): bool
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Obtener la venta y su lote
            $stmt = $this->pdo->prepare('
            SELECT ls.lot_id, ls.payment_status
            FROM lot_sales ls
            WHERE ls.id = ?
        ');
            $stmt->execute([$id]);
            $sale = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sale) {
                throw new Exception('Venta no encontrada');
            }

            if ($sale['payment_status'] === 'cancelado') {
                throw new Exception('La venta ya está cancelada');
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
            $stmt = $this->pdo->prepare('
            INSERT INTO lot_status_history 
            (lot_id, old_status, new_status, reason, changed_by, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ');
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

    /**
     * Calcula el estado actual de pago, mora pendiente y próxima fecha de vencimiento de una venta
     * @param  int   $saleId ID de la venta
     * @return array Información detallada del estado
     */
    public function getPaymentStatusDetails(int $saleId): array
    {
        $sale = $this->getById($saleId);
        if (!$sale) {
            return [
                'status'          => 'desconocido',
                'late_fee_due'    => 0.00,
                'next_due_date'   => null,
                'days_until_due'  => 0,
                'days_overdue'    => 0,
                'is_within_grace' => false
            ];
        }

        // Valores de configuración de la venta
        $dueDay         = (int) ($sale['due_day_of_month'] ?? 7);           // día del mes de vencimiento
        $graceDays      = (int) ($sale['grace_days'] ?? 7);              // días de gracia
        $lateRate       = (float) ($sale['late_fee_rate'] ?? 10.00) / 100; // % de mora
        $monthlyPayment = (float) ($sale['monthly_fixed_payment'] ?? 0); // cuota mensual fija

        if ($monthlyPayment <= 0) {
            // No hay cuotas → no aplica mora
            return [
                'status'          => $sale['payment_status'],
                'late_fee_due'    => 0.00,
                'next_due_date'   => null,
                'days_until_due'  => 0,
                'days_overdue'    => 0,
                'is_within_grace' => false
            ];
        }

        $today = new DateTime();

        // Encontrar la fecha de la última cuota pagada (solo cuotas fijas/minimas)
        $stmt = $this->pdo->prepare("
        SELECT MAX(payment_date) 
        FROM lot_payments 
        WHERE lot_sale_id = ? 
          AND payment_type IN ('cuota_fija', 'cuota_minima')
    ");
        $stmt->execute([$saleId]);
        $lastPaidDate = $stmt->fetchColumn() ?: $sale['sale_date'];

        // Calcular la siguiente fecha de vencimiento
        $lastDate = new DateTime($lastPaidDate);
        $nextDue  = clone $lastDate;
        $nextDue->modify('+1 month');
        $nextDue->setDate((int)$nextDue->format('Y'), (int)$nextDue->format('m'), $dueDay);

        // Si la fecha ya pasó, avanzar al siguiente mes
        while ($today > $nextDue) {
            $nextDue->modify('+1 month');
        }

        // Fecha límite con días de gracia
        $dueWithGrace = clone $nextDue;
        $dueWithGrace->modify("+{$graceDays} days");

        // Cálculos finales
        $daysUntilDue  = (int) $today->diff($nextDue)->format('%r%a'); // negativo si ya pasó
        $daysOverdue   = $today > $dueWithGrace ? (int) $dueWithGrace->diff($today)->days : 0;
        $isWithinGrace = $today > $nextDue && $today <= $dueWithGrace;

        $status     = $sale['payment_status'];
        $lateFeeDue = 0.00;

        if ($daysOverdue > 0) {
            $status     = 'mora';
            $lateFeeDue = $monthlyPayment * $lateRate;
        } elseif ($isWithinGrace) {
            $status = 'atrasado';
        } elseif ($daysUntilDue > 0) {
            $status = 'al_dia';
        }

        return [
            'status'          => $status,
            'late_fee_due'    => round($lateFeeDue, 2),
            'next_due_date'   => $nextDue->format('Y-m-d'),
            'days_until_due'  => $daysUntilDue,
            'days_overdue'    => $daysOverdue,
            'is_within_grace' => $isWithinGrace
        ];
    }

    public function updateStatusFromCalculation(int $saleId): void
    {
        $details = $this->getPaymentStatusDetails($saleId);
        $stmt    = $this->pdo->prepare('UPDATE lot_sales SET payment_status = ? WHERE id = ?');
        $stmt->execute([$details['status'], $saleId]);
    }
}
