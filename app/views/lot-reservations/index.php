<!-- app/views/lot-reservations/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-calendar-check me-2"></i>
        Listado de Reservas
    </h2>
    <a href="<?= BASE_URL ?>lot-reservations/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nueva Reserva
    </a>
</div>

<?php if (empty($reservations)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>No hay reservas registradas aún.</h5>
        <p>¡Crea la primera para empezar a gestionar apartados de lotes!</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 responsive-vertical">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Lote</th>
                            <th>Cliente</th>
                            <th>Fecha Reserva</th>
                            <th>Expira</th>
                            <th>Monto (S/)</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $res): ?>
                            <tr>
                                <td><strong><?= $res['id'] ?></strong></td>
                                <td><?= htmlspecialchars($res['lot_number']) ?></td>
                                <td><?= htmlspecialchars($res['client_name']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($res['reservation_date'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($res['expiration_date'])) ?></td>
                                <td>S/ <?= number_format($res['amount'], 2) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match ($res['reservation_status']) {
                                        'activa'     => 'bg-success',
                                        'confirmada' => 'bg-primary',
                                        'expirada'   => 'bg-warning',
                                        'cancelada'  => 'bg-danger',
                                        default      => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge rounded-pill <?= $badgeClass ?>">
                                        <?= ucfirst($res['reservation_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>lot-reservations/edit/<?= $res['id'] ?>"
                                            class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($res['reservation_status'] === 'activa' || $res['reservation_status'] === 'confirmada'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-1" 
                                                data-bs-toggle="modal" data-bs-target="#cancelModal<?= $res['id'] ?>" 
                                                title="Cancelar reserva">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($res['reservation_status'] === 'activa'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success ms-1" 
                                                data-bs-toggle="modal" data-bs-target="#confirmSaleModal<?= $res['id'] ?>" 
                                                title="Confirmar como venta definitiva">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Modal de confirmación -->
                                        <div class="modal fade" id="cancelModal<?= $res['id'] ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?= $res['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelModalLabel<?= $res['id'] ?>">Cancelar Reserva #<?= $res['id'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="post" action="<?= BASE_URL ?>lot-reservations/cancel/<?= $res['id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                                        <div class="modal-body">
                                                            <p>¿Realmente deseas cancelar esta reserva?</p>
                                                            <p class="text-danger">El lote volverá a estar disponible inmediatamente.</p>
                                                        <div class="mb-3">
                                                            <label for="reason<?= $res['id'] ?>" class="form-label">Motivo de cancelación (opcional)</label>
                                                            <textarea name="reason" id="reason<?= $res['id'] ?>" class="form-control" rows="3" placeholder="Ej: Cliente desistió, problemas de financiamiento..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-danger">Sí, Cancelar Reserva</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Más adelante: botón Cancelar -->

                                        <!-- Modal de confirmación de venta -->
                                        <div class="modal fade" id="confirmSaleModal<?= $res['id'] ?>" tabindex="-1" aria-labelledby="confirmSaleModalLabel<?= $res['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmSaleModalLabel<?= $res['id'] ?>">Confirmar Venta #<?= $res['id'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>¿Desea convertir esta reserva en una venta definitiva?</p>
                                                        <ul>
                                                            <li><strong>Lote:</strong> <?= htmlspecialchars($res['lot_number']) ?></li>
                                                            <li><strong>Cliente:</strong> <?= htmlspecialchars($res['client_name']) ?></li>
                                                            <li><strong>Monto pagado:</strong> S/ <?= number_format($res['amount'], 2) ?></li>
                                                            <li><strong>Fecha expiración:</strong> <?= date('d/m/Y H:i', strtotime($res['expiration_date'])) ?></li>
                                                        </ul>
                                                        <p class="text-success">El lote pasará a estado 'vendido' y se creará un registro de venta.</p>
                                                    </div>
                                                    <form method="post" action="<?= BASE_URL ?>lot-reservations/confirm-sale/<?= $res['id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">Sí, Confirmar Venta</button>
                                                        </div>
                                                    </form>
                                                </div>
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
<?php endif; ?>