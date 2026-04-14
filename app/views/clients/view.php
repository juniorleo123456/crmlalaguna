<!-- app/views/clients/view.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-person-circle me-2 text-primary"></i>
        Ficha del Cliente: <?= htmlspecialchars($client['name']) ?>
    </h2>
    <div>
        <a href="<?= BASE_URL ?>clients/edit/<?= $client['id'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Editar Cliente
        </a>
        <a href="<?= BASE_URL ?>clients" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver al listado
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Columna izquierda: Datos personales -->
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($client['name']) ?></dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($client['email']) ?></dd>

                    <dt class="col-sm-4">Teléfono</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($client['phone'] ?? '-') ?></dd>

                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-<?= $client['status'] === 'active' ? 'success' : 'secondary' ?>">
                            <?= $client['status']                   === 'active' ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </dd>

                    <dt class="col-sm-4">Dirección</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($client['address'] ?? '-') ?></dd>

                    <dt class="col-sm-4">Ciudad</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($client['city'] ?? '-') ?></dd>

                    <dt class="col-sm-4">Empresa</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($client['company_name'] ?? '-') ?></dd>

                    <dt class="col-sm-4">Notas</dt>
                    <dd class="col-sm-8"><?= nl2br(htmlspecialchars($client['notes'] ?? '-')) ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Columna derecha: Proyectos / Servicios asociados -->
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Proyectos y Servicios Asociados</h5>
                <a href="<?= BASE_URL ?>client-services/create?client_id=<?= $client['id'] ?>"
                    class="btn btn-sm btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Asociar Proyecto/Servicio
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($services)): ?>
                    <div class="alert alert-info text-center py-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Este cliente no tiene proyectos ni servicios asociados aún.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Servicio / Proyecto</th>
                                    <th>Estado</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($service['project_title'] ?? $service['service_name']) ?>
                                            <?php if ($service['project_id']): ?>
                                                <small class="text-muted d-block">
                                                    Proyecto ID: <?= $service['project_id'] ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $service['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($service['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $service['start_date'] ? date('d/m/Y', strtotime($service['start_date'])) : '-' ?></td>
                                        <td><?= $service['end_date'] ? date('d/m/Y', strtotime($service['end_date'])) : '-' ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-danger" title="Eliminar asociación">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>