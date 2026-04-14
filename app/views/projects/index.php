<!-- app/views/projects/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-building me-2"></i>
        Listado de Proyectos
    </h2>
    <a href="<?= BASE_URL ?>projects/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Proyecto
    </a>
</div>

<!-- Formulario de búsqueda y filtros -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Búsqueda</label>
                <input type="text" name="search" class="form-control" 
                       value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Título o descripción">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?= $st ?>" <?= ($status ?? '') === $st ? 'selected' : '' ?>>
                            <?= ucfirst($st) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($projects)): ?>
    <div class="alert alert-info text-center py-4">
        <i class="bi bi-info-circle-fill fs-4 me-2"></i>
        No hay proyectos registrados aún. ¡Crea el primero!
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 responsive-vertical">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Progreso</th>
                            <th>Inicio / Fin</th>
                            <th>Creado por</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <tr>
                                <td data-label="ID"><strong><?= htmlspecialchars($project['id']) ?></strong></td>
                                <td data-label="Título">
                                    <i class="bi bi-folder2-open text-primary me-2"></i>
                                    <?= htmlspecialchars($project['title']) ?>
                                </td>
                                <td data-label="Estado">
                                    <?php
                                    $badgeClass = match($project['status']) {
                                        'planificacion' => 'bg-secondary',
                                        'ejecucion'     => 'bg-primary',
                                        'entregado'     => 'bg-success',
                                        'cancelado'     => 'bg-danger',
                                        default         => 'bg-light text-dark'
                                    };
                            $icon = match($project['status']) {
                                'planificacion' => 'bi bi-hourglass-split',
                                'ejecucion'     => 'bi bi-gear-wide-connected',
                                'entregado'     => 'bi bi-check-circle-fill',
                                'cancelado'     => 'bi bi-x-circle-fill',
                                default         => 'bi bi-question-circle'
                            };
                            ?>
                                    <span class="badge rounded-pill <?= $badgeClass ?>">
                                        <i class="<?= $icon ?> me-1"></i>
                                        <?= ucfirst($project['status']) ?>
                                    </span>
                                </td>
                                <td data-label="Progreso">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: <?= $project['progress'] ?>%;" 
                                             aria-valuenow="<?= $project['progress'] ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?= $project['progress'] ?>%
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Inicio / Fin">
                                    <?= $project['start_date'] ? date('d/m/Y', strtotime($project['start_date'])) : '-' ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= $project['end_date'] ? date('d/m/Y', strtotime($project['end_date'])) : '-' ?>
                                    </small>
                                </td>
                                <td data-label="Creado por">
                                    <?= htmlspecialchars($project['created_by_name'] ?? '-') ?>
                                </td>
                                <td data-label="Acciones" class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>projects/edit/<?= $project['id'] ?>" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>projects/change-status/<?= $project['id'] ?>" 
                                           class="btn btn-outline-info" title="Cambiar estado">
                                            <i class="bi bi-arrow-repeat"></i>
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
    <!-- Paginación Bootstrap -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Paginación de proyectos" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page                                                                                                                                         <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&client_id=<?= $client_id ?>" <?= $page <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Anterior</a>
                </li>

                <?php
                $startPage = max(1, $page - 2);
        $endPage           = min($totalPages, $page + 2);
        for ($p = $startPage; $p <= $endPage; $p++): ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&client_id=<?= $client_id ?>">
                            <?= $p ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page                                                                                                                                                   >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&client_id=<?= $client_id ?>" <?= $page >= $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Siguiente</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>