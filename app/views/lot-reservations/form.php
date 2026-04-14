<!-- app/views/lot-reservations/form.php -->

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <div class="row g-3">
                <!-- Lote -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Lote *</label>
                    <select name="lot_id" class="form-select" required>
                        <option value="">-- Seleccionar lote disponible --</option>
                        <?php foreach ($lots as $lot): ?>
                            <?php if ($mode === 'create' && $lot['status'] !== 'disponible') {
                                continue;
                            } ?>
                            <option value="<?= $lot['id'] ?>" <?= ($data['lot_id'] ?? 0) == $lot['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lot['lot_number']) ?> - <?= htmlspecialchars($lot['block_name']) ?>
                                (Estado: <?= ucfirst($lot['status']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($mode === 'create'): ?>
                        <small class="text-muted d-block mt-1">Solo se muestran lotes disponibles.</small>
                    <?php endif; ?>
                </div>

                <!-- Cliente -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Cliente *</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Seleccionar cliente --</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>" <?= ($data['client_id'] ?? 0) == $client['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($client['name']) ?> (<?= htmlspecialchars($client['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha de reserva -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Fecha de reserva</label>
                    <input type="datetime-local" name="reservation_date" class="form-control"
                        value="<?= htmlspecialchars($data['reservation_date'] ?? date('Y-m-d\TH:i')) ?>">
                </div>

                <!-- Fecha de expiración -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Expira el *</label>
                    <input type="datetime-local" name="expiration_date" class="form-control" required
                        value="<?= htmlspecialchars($data['expiration_date'] ?? date('Y-m-d\TH:i', strtotime('+30 days'))) ?>">
                </div>

                <!-- Monto de reserva -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Monto de reserva (S/) *</label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="1" required
                        value="<?= htmlspecialchars($data['amount'] ?? '300.00') ?>">
                </div>

                <!-- Notas -->
                <div class="col-12">
                    <label class="form-label fw-bold">Notas / Observaciones</label>
                    <textarea name="notes" class="form-control" rows="3"
                        placeholder="Detalles adicionales, condiciones especiales..."><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>
                    <?= $mode === 'create' ? 'Crear Reserva' : 'Guardar Cambios' ?>
                </button>
                <a href="<?= BASE_URL ?>lot-reservations" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                </a>
            </div>
        </form>
    </div>
</div>