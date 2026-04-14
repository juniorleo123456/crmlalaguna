<!-- app/views/lots/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-grid-3x3 me-2"></i>
        Listado de Lotes
    </h2>
    <a href="<?= BASE_URL ?>lots/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nuevo Lote
    </a>
</div>

<!-- Filtro por manzana -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Manzana</label>
                <select name="block_id" class="form-select">
                    <option value="">Todas las manzanas</option>
                    <?php foreach ($blocks as $block): ?>
                        <option value="<?= $block['id'] ?>" <?= ($block_id ?? 0) == $block['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($block['name']) ?> (<?= htmlspecialchars($block['project_title']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($lots)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>No hay lotes registrados aún.</h5>
        <p>¡Crea el primero para empezar a gestionar tu inventario!</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 responsive-vertical">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>N° Lote</th>
                            <th>Manzana / Proyecto</th>
                            <th>Área (m²)</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Características</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lots as $lot): ?>
                            <tr>
                                <td data-label="ID"><strong><?= htmlspecialchars($lot['id']) ?></strong></td>
                                <td data-label="N° Lote">
                                    <?= htmlspecialchars($lot['lot_number']) ?>
                                </td>
                                <td data-label="Manzana / Proyecto">
                                    <?= htmlspecialchars($lot['block_name'] ?? '-') ?>
                                    <small class="text-muted d-block">
                                        <?= htmlspecialchars($lot['project_title'] ?? '-') ?>
                                    </small>
                                </td>
                                <td data-label="Área">
                                    <?= number_format($lot['area'], 2) ?> m²
                                </td>
                                <td data-label="Precio">
                                    S/ <?= number_format($lot['price'], 2) ?>
                                </td>
                                <td data-label="Estado">
                                    <?php
                                    $badgeClass = match ($lot['status']) {
                                        'disponible' => 'bg-success',
                                        'reservado'  => 'bg-warning',
                                        'vendido'    => 'bg-primary',
                                        'mora'       => 'bg-danger',
                                        'cancelado'  => 'bg-secondary',
                                        default      => 'bg-light text-dark'
                                    };
                            ?>
                                    <span class="badge rounded-pill <?= $badgeClass ?>">
                                        <?= ucfirst($lot['status']) ?>
                                    </span>
                                </td>
                                <td data-label="Características">
                                    <?php
                            $features = [];
                            if ($lot['is_corner']) {
                                $features[] = 'Esquinero';
                            }
                            if ($lot['faces_park']) {
                                $features[] = 'Frente a parque';
                            }
                            if ($lot['faces_main_street']) {
                                $features[] = 'Frente a avenida';
                            }
                            if ($lot['jiron_principal']) {
                                $features[] = 'Jirón principal';
                            }
                            if ($lot['calle_1']) {
                                $features[] = 'Calle 1';
                            }
                            if ($lot['calle_2']) {
                                $features[] = 'Calle 2';
                            }
                            if ($lot['pasaje_1_parque']) {
                                $features[] = 'Pasaje 1 (parque)';
                            }
                            if ($lot['pasaje_2']) {
                                $features[] = 'Pasaje 2';
                            }
                            echo $features ? implode(', ', $features) : '-';
                            ?>
                                </td>
                                <td data-label="Acciones" class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>lots/edit/<?= $lot['id'] ?>"
                                            class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>lots/toggle/<?= $lot['id'] ?>"
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
    <!-- Paginación -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Paginación de lotes" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&block_id=<?= $block_id ?>&status=<?= urlencode($status) ?>"
                        <?= $page <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Anterior</a>
                </li>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $p ?>&block_id=<?= $block_id ?>&status=<?= urlencode($status) ?>">
                            <?= $p ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>&block_id=<?= $block_id ?>&status=<?= urlencode($status) ?>"
                        <?= $page >= $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Siguiente</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>