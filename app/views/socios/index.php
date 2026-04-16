<!-- app/views/socios/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-person-badge me-2"></i>
        Listado de Socios
    </h2>
    <a href="<?= BASE_URL ?>socios/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Socio
    </a>
</div>

<?php if (empty($socios)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>Aún no hay socios registrados.</h5>
        <p>Registra el primero para comenzar a gestionar comisiones.</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Empresa</th>
                            <th>Comisión</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($socios as $socio): ?>
                            <tr>
                                <td><strong><?= $socio['id'] ?></strong></td>
                                <td><?= htmlspecialchars($socio['name']) ?></td>
                                <td><?= htmlspecialchars($socio['email']) ?></td>
                                <td><?= htmlspecialchars($socio['phone'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($socio['nombre_empresa'] ?? '—') ?></td>
                                <td>
                                    <strong><?= number_format($socio['commission_rate'] ?? 0, 2) ?>%</strong>
                                </td>
                                <td>
                                    <?php if ($socio['status'] === 'active'): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>socios/edit/<?= $socio['id'] ?>" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>socios/toggle-status/<?= $socio['id'] ?>" 
                                           class="btn btn-outline-<?= $socio['status'] === 'active' ? 'warning' : 'success' ?>" 
                                           title="<?= $socio['status']                 === 'active' ? 'Desactivar' : 'Activar' ?>">
                                            <i class="bi bi-<?= $socio['status']       === 'active' ? 'toggle-off' : 'toggle-on' ?>"></i>
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