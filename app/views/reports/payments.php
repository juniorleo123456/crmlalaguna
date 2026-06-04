<!-- app/views/reports/payments.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= BASE_URL ?>reports" class="btn btn-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
        <h2 class="h4 d-inline mb-0">Reporte de Pagos</h2>
    </div>
    <button class="btn btn-primary btn-sm" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Descargar PDF
    </button>
</div>

<!-- Filtros -->
<div class="card mb-4 no-print">
    <div class="card-header bg-light">
        <h5 class="mb-0">Filtros</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label for="date_from" class="form-label">Desde</label>
                <input type="date" class="form-control form-control-sm" id="date_from" name="date_from" value="<?= htmlspecialchars($filters['dateFrom']) ?>">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Hasta</label>
                <input type="date" class="form-control form-control-sm" id="date_to" name="date_to" value="<?= htmlspecialchars($filters['dateTo']) ?>">
            </div>
            <div class="col-md-3">
                <label for="client" class="form-label">Cliente</label>
                <select class="form-select form-select-sm" id="client" name="client">
                    <option value="">Todos</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filters['client'] == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Tipo Pago</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Todos</option>
                    <?php foreach ($paymentTypes as $type): ?>
                        <option value="<?= $type ?>" <?= $filters['status'] == $type ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_', ' ', $type)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Datos -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($payments)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha Pago</th>
                            <th>Cliente</th>
                            <th>Lote</th>
                            <th class="text-end">Monto</th>
                            <th>Tipo Pago</th>
                            <th>Registrado Por</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalAmount = 0;
                        foreach ($payments as $payment):
                            $totalAmount += $payment['amount'] ?? 0;
                        ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></td>
                                <td><?= htmlspecialchars($payment['client_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($payment['lot_number'] ?? 'N/A') ?></td>
                                <td class="text-end"><strong>S/ <?= number_format($payment['amount'] ?? 0, 2) ?></strong></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= ucfirst(str_replace('_', ' ', $payment['payment_type'] ?? 'N/A')) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($payment['registered_by_name'] ?? 'Sistema') ?></td>
                                <td class="small"><?= htmlspecialchars(substr($payment['notes'] ?? '', 0, 30)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-light fw-bold">
                            <td colspan="3">TOTAL</td>
                            <td class="text-end">S/ <?= number_format($totalAmount, 2) ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-muted small mt-3">
                Total de registros: <?= count($payments) ?> | Total de pagos: S/ <?= number_format($totalAmount, 2) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>No hay pagos que coincidan con los filtros aplicados.
            </div>
        <?php endif; ?>
    </div>
</div>

<style media="print">
    .no-print { display: none !important; }
    body { font-size: 11pt; }
    table { border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f9f9f9; }
</style>
