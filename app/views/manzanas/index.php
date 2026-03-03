<!-- app/views/manzanas/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-grid-3x3-gap me-2"></i>
        Listado de Manzanas
    </h2>
    <a href="<?= BASE_URL ?>blocks/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Nueva Manzana
    </a>
</div>

<?php if (empty($blocks)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>No hay manzanas registradas aún.</h5>
        <p>¡Crea la primera para empezar a organizar tus lotes!</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 responsive-vertical">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Proyecto</th>
                            <th>Nombre</th>
                            <th>Total Lotes</th>
                            <th>Pago Mensual Mín.</th>
                            <th>Cuota Inicial</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blocks as $block): ?>
                            <tr>
                                <td data-label="ID"><strong><?= htmlspecialchars($block['id']) ?></strong></td>
                                <td data-label="Proyecto">
                                    <?= htmlspecialchars($block['project_title'] ?? '-') ?>
                                </td>
                                <td data-label="Nombre">
                                    <i class="bi bi-grid-3x3-gap text-primary me-2"></i>
                                    <?= htmlspecialchars($block['name']) ?>
                                </td>
                                <td data-label="Total Lotes">
                                    <?= htmlspecialchars($block['total_lots']) ?>
                                </td>
                                <td data-label="Pago Mensual Mín.">
                                    S/ <?= number_format($block['min_monthly_payment'], 2) ?>
                                </td>
                                <td data-label="Cuota Inicial">
                                    S/ <?= number_format($block['initial_payment'], 2) ?>
                                </td>
                                <td data-label="Estado">
                                    <span class="badge rounded-pill bg-<?= $block['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <i class="bi bi-<?= $block['status'] === 'active' ? 'check-circle' : 'slash-circle' ?> me-1"></i>
                                        <?= $block['status'] === 'active' ? 'Activa' : 'Inactiva' ?>
                                    </span>
                                </td>
                                <td data-label="Acciones" class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>blocks/edit/<?= $block['id'] ?>"
                                            class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>blocks/toggle/<?= $block['id'] ?>"
                                            class="btn btn-outline-<?= $block['status'] === 'active' ? 'warning' : 'success' ?>"
                                            title="<?= $block['status'] === 'active' ? 'Desactivar' : 'Activar' ?>">
                                            <i class="bi bi-<?= $block['status'] === 'active' ? 'toggle-off' : 'toggle-on' ?>"></i>
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