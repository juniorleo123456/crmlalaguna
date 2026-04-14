<!-- app/views/clients/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-people me-2"></i>
        Listado de Clientes
    </h2>
    <a href="<?= BASE_URL ?>clients/create" class="btn btn-success">
        <i class="bi bi-person-plus me-2"></i>Nuevo Cliente
    </a>
</div>

<?php if (empty($clients)): ?>
    <div class="alert alert-info text-center py-4">
        <i class="bi bi-info-circle-fill fs-4 me-2"></i>
        No hay clientes registrados aún. ¡Crea el primero!
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 responsive-vertical">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Ciudad / Empresa</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td data-label="ID"><strong><?= htmlspecialchars($client['id']) ?></strong></td>
                                <td data-label="Nombre">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    <?= htmlspecialchars($client['name']) ?>
                                </td>
                                <td data-label="Email">
                                    <i class="bi bi-envelope text-info me-2"></i>
                                    <?= htmlspecialchars($client['email']) ?>
                                </td>
                                <td data-label="Teléfono">
                                    <?= htmlspecialchars($client['phone'] ?? '-') ?>
                                </td>
                                <td data-label="Ciudad / Empresa">
                                    <?= htmlspecialchars($client['city'] ?? '-') ?>
                                    <?php if (!empty($client['company_name'])): ?>
                                        <div class="small text-muted">
                                            <i class="bi bi-building me-1"></i>
                                            <?= htmlspecialchars($client['company_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Estado">
                                    <span class="badge rounded-pill bg-<?= $client['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <i class="bi bi-<?= $client['status']                === 'active' ? 'check-circle' : 'slash-circle' ?> me-1"></i>
                                        <?= $client['status']                                === 'active' ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td data-label="Acciones" class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>clients/view/<?= $client['id'] ?>" 
                                            class="btn btn-outline-info" title="Ver ficha">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>clients/edit/<?= $client['id'] ?>"
                                            class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>clients/toggle/<?= $client['id'] ?>"
                                            class="btn btn-outline-<?= $client['status'] === 'active' ? 'warning' : 'success' ?>"
                                            title="<?= $client['status']                 === 'active' ? 'Desactivar' : 'Activar' ?>">
                                            <i class="bi bi-<?= $client['status']        === 'active' ? 'toggle-off' : 'toggle-on' ?>"></i>
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