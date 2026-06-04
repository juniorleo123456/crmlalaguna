<!-- app/views/reports/sales.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= BASE_URL ?>reports" class="btn btn-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
        <h2 class="h4 d-inline mb-0">Reporte de Ventas</h2>
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
            <div class="col-md-2">
                <label for="project" class="form-label">Proyecto</label>
                <select class="form-select form-select-sm" id="project" name="project">
                    <option value="">Todos</option>
                    <?php foreach ($projects as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $filters['project'] == $p['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
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
            <div class="col-md-2">
                <label for="status" class="form-label">Estado Pago</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Todos</option>
                    <option value="al_dia" <?= $filters['status'] == 'al_dia' ? 'selected' : '' ?>>Al Día</option>
                    <option value="atrasado" <?= $filters['status'] == 'atrasado' ? 'selected' : '' ?>>Atrasado</option>
                    <option value="mora" <?= $filters['status'] == 'mora' ? 'selected' : '' ?>>Mora</option>
                    <option value="cancelado" <?= $filters['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
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
        <?php if (!empty($sales)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Lote</th>
                            <th>Proyecto</th>
                            <th>Bloque</th>
                            <th class="text-end">Monto Total</th>
                            <th class="text-end">Inicial</th>
                            <th class="text-end">Balance</th>
                            <th>Estado Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($sale['sale_date'])) ?></td>
                                <td><?= htmlspecialchars($sale['client_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($sale['lot_number'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($sale['project_title'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($sale['block_name'] ?? 'N/A') ?></td>
                                <td class="text-end">S/ <?= number_format($sale['total_price'] ?? 0, 2) ?></td>
                                <td class="text-end">S/ <?= number_format($sale['initial_payment'] ?? 0, 2) ?></td>
                                <td class="text-end">S/ <?= number_format($sale['balance'] ?? 0, 2) ?></td>
                                <td>
                                    <?php
                                    $status = $sale['payment_status'];
                                    $badge = match($status) {
                                        'al_dia' => 'bg-success',
                                        'atrasado' => 'bg-warning',
                                        'mora' => 'bg-danger',
                                        'cancelado' => 'bg-secondary',
                                        default => 'bg-info'
                                    };
                                    ?>
                                    <span class="badge <?= $badge ?>">
                                        <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-muted small mt-3">
                Total de registros: <?= count($sales) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>No hay ventas que coincidan con los filtros aplicados.
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
