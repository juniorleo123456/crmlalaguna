<!-- app/views/commissions/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-graph-up-arrow me-2"></i>
        Listado de Comisiones Pagadas
    </h2>
    <a href="<?= BASE_URL ?>partners/comisiones/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Registrar Nueva Comisión
    </a>
</div>

<?php if (empty($commissions)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>Aún no hay comisiones registradas.</h5>
        <p>Registra la primera comisión pagada a un socio.</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Socio</th>
                            <th>Lote / Proyecto</th>
                            <th>Monto Pagado</th>
                            <th>Notas</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commissions as $com): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($com['payment_date'])) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($com['socio_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($com['nombre_empresa'] ?? '') ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($com['lot_number'] ?? '—') ?>
                                    <?php if (!empty($com['block_name'])): ?>
                                        <small class="text-muted d-block">Manzana <?= htmlspecialchars($com['block_name']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="text-success">S/ <?= number_format($com['amount'], 2) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($com['notes'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($com['registered_by_name'] ?? 'Sistema') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>