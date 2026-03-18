<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-cart-check me-2"></i>
        Listado de Ventas / Contratos
    </h2>
    <a href="<?= BASE_URL ?>lot-sales/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nueva Venta
    </a>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Cliente</label>
                <select name="client_id" class="form-select">
                    <option value="">Todos los clientes</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>" <?= $filters['client_id'] == $client['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($client['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Estado de pago</label>
                <select name="payment_status" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($statuses as $st): ?>
                        <option value="<?= $st ?>" <?= $filters['payment_status'] === $st ? 'selected' : '' ?>>
                            <?= ucfirst($st) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Buscar (lote o cliente)</label>
                <input type="text" name="search" class="form-control"
                    value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                    placeholder="Número de lote o nombre...">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($sales)): ?>
    <div class="alert alert-info text-center py-5">
        No hay ventas que coincidan con los filtros.
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Lote</th>
                            <th>Cliente</th>
                            <th>Fecha Venta</th>
                            <th>Precio Total</th>
                            <th>Saldo Pendiente</th>
                            <th>Estado Pago</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td><?= $sale['id'] ?></td>
                                <td><?= htmlspecialchars($sale['lot_number']) ?></td>
                                <td><?= htmlspecialchars($sale['client_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($sale['sale_date'])) ?></td>
                                <td>
    S/ <?= number_format($sale['total_price'], 2) ?><br>
    <small>
        Interés: S/ <?= number_format(
    ($sale['total_with_interest'] ?? $sale['total_price']) - $sale['total_price'], 
    2
) ?>
    </small>
</td>

<td>
    <strong>S/ <?= number_format($sale['balance'], 2) ?></strong>

    <small class="text-muted">
        (Pagado: 
        S/ <?= number_format(
            ($sale['total_with_interest'] ?? $sale['total_price']) - $sale['balance'], 
            2
        ) ?>)
    </small>

    <?php if ($sale['balance'] <= 0): ?>
        <span class="badge bg-success ms-2">Pagado</span>

    <?php elseif ($sale['balance'] < ($sale['total_with_interest'] ?? $sale['total_price']) * 0.1): ?>
        <span class="badge bg-warning ms-2">Casi pagado</span>

    <?php else: ?>
        <span class="badge bg-info ms-2">Pendiente</span>
    <?php endif; ?>
</td>
                                <td>
                                    <span class="badge bg-<?= match ($sale['payment_status']) {
                                                                'al_dia' => 'success',
                                                                'atrasado' => 'warning',
                                                                'mora' => 'danger',
                                                                'cancelado' => 'secondary',
                                                                default => 'light text-dark'
                                                            } ?>">
                                        <?= ucfirst($sale['payment_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>lot-sales/edit/<?= $sale['id'] ?>"
                                            class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if ($sale['payment_status'] !== 'cancelado'): ?>
                                            <button type="button" class="btn btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#cancelModal<?= $sale['id'] ?>"
                                                title="Cancelar venta">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Modal de confirmación (uno por fila) -->
                                    <div class="modal fade" id="cancelModal<?= $sale['id'] ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?= $sale['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelModalLabel<?= $sale['id'] ?>">Cancelar Venta #<?= $sale['id'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="post" action="<?= BASE_URL ?>lot-sales/cancel/<?= $sale['id'] ?>">
                                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                                    <div class="modal-body">
                                                        <p>¿Realmente deseas cancelar esta venta?</p>
                                                        <p class="text-danger">El lote volverá a estar disponible y la venta quedará anulada.</p>
                                                        <div class="mb-3">
                                                            <label for="reason<?= $sale['id'] ?>" class="form-label">Motivo de cancelación (opcional)</label>
                                                            <textarea name="reason" id="reason<?= $sale['id'] ?>" class="form-control" rows="3" placeholder="Ej: Cliente desistió, problemas de financiamiento..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-danger">Sí, Cancelar Venta</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
        <nav aria-label="Paginación de ventas" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&client_id=<?= $filters['client_id'] ?>&payment_status=<?= urlencode($filters['payment_status']) ?>&search=<?= urlencode($filters['search']) ?>"
                        <?= $page <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Anterior</a>
                </li>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $p ?>&client_id=<?= $filters['client_id'] ?>&payment_status=<?= urlencode($filters['payment_status']) ?>&search=<?= urlencode($filters['search']) ?>">
                            <?= $p ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>&client_id=<?= $filters['client_id'] ?>&payment_status=<?= urlencode($filters['payment_status']) ?>&search=<?= urlencode($filters['search']) ?>"
                        <?= $page >= $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Siguiente</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>