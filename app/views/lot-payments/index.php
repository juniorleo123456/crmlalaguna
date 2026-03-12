<!-- app/views/lot-payments/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-cash-coin me-2"></i>
        Listado de Pagos
    </h2>
    <a href="<?= BASE_URL ?>lot-payments/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Pago
    </a>
</div>

<!-- Filtros rápidos (opcional: puedes ampliarlo más adelante) -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Buscar por cliente o lote</label>
                <input type="text" name="search" class="form-control"
                    value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                    placeholder="Nombre, email o número de lote...">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($payments)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>Aún no hay pagos registrados.</h5>
        <p>¡Registra el primero para empezar a llevar el control financiero!</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Monto (S/)</th>
                            <th>Método</th>
                            <th>Lote / Venta</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><strong><?= $payment['id'] ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($payment['payment_date'])) ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $payment['payment_type'])) ?></td>
                                <td>S/ <?= number_format($payment['amount'], 2) ?></td>
                                <td><?= ucfirst($payment['payment_method']) ?></td>
                                <td>
                                    <?php if ($payment['sale_id']): ?>
                                        Venta #<?= $payment['sale_id'] ?> - Lote <?= htmlspecialchars($payment['lot_number'] ?? 'N/A') ?>
                                    <?php elseif ($payment['reservation_id']): ?>
                                        Reserva #<?= $payment['reservation_id'] ?> - Lote <?= htmlspecialchars($payment['lot_number'] ?? 'N/A') ?>
                                    <?php else: ?>
                                        <span class="text-muted">No asociado</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($payment['client_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($payment['is_late']): ?>
                                        <span class="badge bg-danger">Mora (S/ <?= number_format($payment['late_fee'], 2) ?>)</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Al día</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($payment['receipt_file']): ?>
                                            <a href="<?= BASE_URL . $payment['receipt_file'] ?>"
                                                class="btn btn-outline-primary" target="_blank" title="Ver Boleta">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        <?php endif; ?>
                                        <!-- Más adelante: botón editar -->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>