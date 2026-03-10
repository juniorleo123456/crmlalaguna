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
                        <option value="">-- Seleccionar lote --</option>
                        <?php foreach ($lots as $lot): ?>
                            <option value="<?= $lot['id'] ?>" <?= ($data['lot_id'] ?? 0) == $lot['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lot['lot_number']) ?> - <?= htmlspecialchars($lot['block_name']) ?> (<?= htmlspecialchars($lot['project_title']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if ($mode === 'edit' && !empty($data['lot_status'])): ?>
                    <div class="col-12 mt-2">
                        <small class="text-muted">
                            Lote asociado actual: <strong><?= htmlspecialchars($data['lot_number'] ?? 'N/A') ?></strong> 
                            (Estado: <strong class="text-<?= match($data['lot_status']) {
                                'disponible' => 'success',
                                'reservado'  => 'warning',
                                'vendido'    => 'primary',
                                'mora'       => 'danger',
                                'cancelado'  => 'secondary',
                                default      => 'muted'
                                } ?>">
                                <?= ucfirst($data['lot_status']) ?>
                                    </strong>)
                        </small>
                    </div>
                <?php endif; ?>

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

                <!-- Fecha de venta -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Fecha de venta *</label>
                    <input type="date" name="sale_date" class="form-control" required
                           value="<?= htmlspecialchars($data['sale_date'] ?? date('Y-m-d')) ?>">
                </div>

                <!-- Precio total -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Precio total (S/) *</label>
                    <input type="number" name="total_price" class="form-control" step="0.01" min="0" required
                           value="<?= htmlspecialchars($data['total_price'] ?? '') ?>">
                </div>

                <!-- Cuota inicial -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Cuota inicial (S/)</label>
                    <input type="number" name="initial_payment" class="form-control" step="0.01" min="0"
                           value="<?= htmlspecialchars($data['initial_payment'] ?? '') ?>">
                </div>

                <!-- Plazo (meses) -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Plazo (meses)</label>
                    <input type="number" name="payment_term" class="form-control" min="0"
                           value="<?= htmlspecialchars($data['payment_term'] ?? '') ?>">
                </div>

                <!-- Tasa de interés -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tasa de interés (%)</label>
                    <input type="number" name="interest_rate" class="form-control" step="0.01" min="0"
                           value="<?= htmlspecialchars($data['interest_rate'] ?? '0.00') ?>">
                </div>

                <!-- Cuota fija mensual -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Cuota fija mensual (S/)</label>
                    <input type="number" name="monthly_fixed_payment" class="form-control" step="0.01" min="0"
                           value="<?= htmlspecialchars($data['monthly_fixed_payment'] ?? '') ?>">
                </div>

                <!-- Notas -->
                <div class="col-12">
                    <label class="form-label fw-bold">Notas / Observaciones</label>
                    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Guardar Venta
                </button>
                <a href="<?= BASE_URL ?>lot-sales" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>