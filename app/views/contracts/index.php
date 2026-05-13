<!-- app/views/contracts/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-file-earmark-text me-2"></i>
        Listado de Contratos
    </h2>
    <a href="<?= BASE_URL ?>contracts/create" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Subir Nuevo Contrato
    </a>
</div>

<?php if (empty($contracts)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>Aún no hay contratos registrados.</h5>
        <p>Sube el primer contrato para un cliente.</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Tipo de Contrato</th>
                            <th>Archivo</th>
                            <th>Fecha Subida</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contracts as $contract): ?>
                            <tr>
                                <td><?= htmlspecialchars($contract['client_name'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($contract['contract_type']) ?></td>
                                <td>
                                    <a href="<?= BASE_URL . $contract['file_path'] ?>" target="_blank" class="text-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> 
                                        <?= htmlspecialchars($contract['file_name']) ?>
                                    </a>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($contract['created_at'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $contract['status'] === 'signed' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($contract['status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>contracts/view/<?= $contract['id'] ?>" 
                                       class="btn btn-outline-info btn-sm" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>