<!-- app/views/reports/lots.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= BASE_URL ?>reports" class="btn btn-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
        <h2 class="h4 d-inline mb-0">Reporte de Lotes</h2>
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
            <div class="col-md-4">
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
            <div class="col-md-4">
                <label for="status" class="form-label">Estado Lote</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Todos</option>
                    <?php foreach ($lotStatuses as $s): ?>
                        <option value="<?= $s ?>" <?= $filters['status'] == $s ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_', ' ', $s)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Resumen de Estadísticas -->
<?php
$statusCount = [];
foreach ($lotStatuses as $status) {
    $statusCount[$status] = 0;
}
foreach ($lots as $lot) {
    if (isset($statusCount[$lot['status']])) {
        $statusCount[$lot['status']]++;
    }
}
?>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card text-center border-success">
            <div class="card-body p-2">
                <h6 class="text-muted mb-0">Disponibles</h6>
                <h3 class="text-success mb-0"><?= $statusCount['disponible'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-primary">
            <div class="card-body p-2">
                <h6 class="text-muted mb-0">Vendidos</h6>
                <h3 class="text-primary mb-0"><?= $statusCount['vendido'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-warning">
            <div class="card-body p-2">
                <h6 class="text-muted mb-0">Reservados</h6>
                <h3 class="text-warning mb-0"><?= $statusCount['reservado'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-danger">
            <div class="card-body p-2">
                <h6 class="text-muted mb-0">Mora</h6>
                <h3 class="text-danger mb-0"><?= $statusCount['mora'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-secondary">
            <div class="card-body p-2">
                <h6 class="text-muted mb-0">Cancelados</h6>
                <h3 class="text-secondary mb-0"><?= $statusCount['cancelado'] ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Datos -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($lots)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Lote #</th>
                            <th>Proyecto</th>
                            <th>Bloque</th>
                            <th class="text-end">Área (m²)</th>
                            <th class="text-end">Frente (m)</th>
                            <th class="text-end">Fondo (m)</th>
                            <th class="text-end">Precio (S/)</th>
                            <th>Estado</th>
                            <th>Cliente (si vendido)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lots as $lot): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($lot['lot_number'] ?? 'N/A') ?></strong></td>
                                <td><?= htmlspecialchars($lot['project_title'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($lot['block_name'] ?? 'N/A') ?></td>
                                <td class="text-end"><?= number_format($lot['area'] ?? 0, 2) ?></td>
                                <td class="text-end"><?= number_format($lot['front'] ?? 0, 2) ?></td>
                                <td class="text-end"><?= number_format($lot['depth'] ?? 0, 2) ?></td>
                                <td class="text-end"><?= number_format($lot['price'] ?? 0, 2) ?></td>
                                <td>
                                    <?php
                                    $status = $lot['status'];
                                    $badge = match($status) {
                                        'disponible' => 'bg-success',
                                        'vendido' => 'bg-primary',
                                        'reservado' => 'bg-warning',
                                        'mora' => 'bg-danger',
                                        'cancelado' => 'bg-secondary',
                                        default => 'bg-info'
                                    };
                                    ?>
                                    <span class="badge <?= $badge ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($lot['client_name'] ?? '—') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-muted small mt-3">
                Total de lotes: <?= count($lots) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>No hay lotes que coincidan con los filtros aplicados.
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
