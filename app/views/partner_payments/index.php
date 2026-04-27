<!-- app/views/partner_payments/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-cash-stack me-2"></i>
        Comisiones Mensuales - Socios
    </h2>
    <a href="<?= BASE_URL ?>partners/comisiones/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Registrar Pago Mensual
    </a>
</div>

<?php if (empty($payments)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>Aún no hay pagos mensuales registrados.</h5>
        <p>Registra el primer pago a un socio.</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Período</th>
                            <th>Socio</th>
                            <th>Empresa</th>
                            <th>Total Ingresos Mes</th>
                            <th>Monto Pagado</th>
                            <th>Tipo</th>
                            <th>Notas</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <!-- Corrección de fecha -->
                                <td><?= !empty($p['periodo']) ? date('m/Y', strtotime($p['periodo'])) : '—' ?></td>
                                <td><?= htmlspecialchars($p['socio_name']) ?></td>
                                <td><?= htmlspecialchars($p['nombre_empresa'] ?? '-') ?></td>
                                <td><strong>S/ <?= number_format($p['total_ingresos_mes'], 2) ?></strong></td>
                                <td><strong class="text-success">S/ <?= number_format($p['monto_pago'], 2) ?></strong></td>
                                <td>
                                    <?= $p['tipo_comision'] === 'percent' 
                                        ? number_format($p['porcentaje'] ?? 0, 2) . '%' 
                                        : 'Monto fijo' ?>
                                </td>
                                <td><?= htmlspecialchars($p['notes'] ?? '-') ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>partners/comisiones/edit/<?= $p['id'] ?>" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>partners/comisiones/delete/<?= $p['id'] ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar este pago mensual?\nEsta acción no se puede deshacer.');"
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
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