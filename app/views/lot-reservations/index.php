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
                                        <a href="<?= BASE_URL ?>lot-reservations/cancel/<?= $res['id'] ?>"
                                            class="btn btn-sm btn-outline-danger ms-1"
                                            onclick="return confirm('¿Realmente quieres cancelar esta reserva? El lote volverá a disponible.');">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                        <!-- Más adelante: botón Cancelar -->
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