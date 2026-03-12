<!-- app/views/lot-payments/form.php -->

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><?= $title ?></h4>
    </div>
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <div class="row g-3">
                <!-- Tipo de asociación -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Asociar a Venta</label>
                    <select name="lot_sale_id" class="form-select">
                        <option value="">-- No asociado a venta --</option>
                        <?php foreach ($sales as $sale): ?>
                            <option value="<?= $sale['id'] ?>" <?= ($data['lot_sale_id'] ?? 0) == $sale['id'] ? 'selected' : '' ?>>
                                Venta #<?= $sale['id'] ?> - Lote <?= htmlspecialchars($sale['lot_number']) ?> - <?= htmlspecialchars($sale['client_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Asociar a Reserva</label>
                    <select name="lot_reservation_id" class="form-select">
                        <option value="">-- No asociado a reserva --</option>
                        <?php foreach ($reservations as $res): ?>
                            <option value="<?= $res['id'] ?>" <?= ($data['lot_reservation_id'] ?? 0) == $res['id'] ? 'selected' : '' ?>>
                                Reserva #<?= $res['id'] ?> - Lote <?= htmlspecialchars($res['lot_number']) ?> - <?= htmlspecialchars($res['client_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha y monto -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Fecha de pago *</label>
                    <input type="date" name="payment_date" class="form-control" required
                        value="<?= htmlspecialchars($data['payment_date'] ?? date('Y-m-d')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Monto (S/) *</label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required
                        value="<?= htmlspecialchars($data['amount'] ?? '') ?>">
                </div>

                <!-- Tipo de pago -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tipo de pago *</label>
                    <select name="payment_type" class="form-select" required>
                        <option value="">-- Seleccionar tipo --</option>
                        <?php foreach ($paymentTypes as $type): ?>
                            <option value="<?= $type ?>" <?= ($data['payment_type'] ?? '') === $type ? 'selected' : '' ?>>
                                <?= ucfirst(str_replace('_', ' ', $type)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Método y número de recibo -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Método de pago</label>
                    <select name="payment_method" class="form-select">
                        <option value="efectivo" <?= ($data['payment_method'] ?? '') === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                        <option value="transferencia" <?= ($data['payment_method'] ?? '') === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        <option value="deposito" <?= ($data['payment_method'] ?? '') === 'deposito' ? 'selected' : '' ?>>Depósito</option>
                        <option value="tarjeta" <?= ($data['payment_method'] ?? '') === 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                        <option value="otro" <?= ($data['payment_method'] ?? '') === 'otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Número de recibo / referencia</label>
                    <input type="text" name="receipt_number" class="form-control"
                        value="<?= htmlspecialchars($data['receipt_number'] ?? '') ?>">
                </div>

                <!-- Subir boleta -->
                <div class="col-12">
                    <label class="form-label fw-bold">Boleta / Comprobante (PDF o imagen)</label>
                    <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if ($mode === 'edit' && $data['receipt_file']): ?>
                        <small class="text-muted d-block mt-1">
                            Boleta actual:
                            <a href="<?= BASE_URL . $data['receipt_file'] ?>" target="_blank">Ver archivo</a>
                        </small>
                    <?php endif; ?>
                </div>

                <!-- Mora (opcional) -->
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="is_late" class="form-check-input" id="is_late" <?= ($data['is_late'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_late">Pago con mora</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Monto de mora (S/)</label>
                    <input type="number" name="late_fee" class="form-control" step="0.01" min="0"
                        value="<?= htmlspecialchars($data['late_fee'] ?? '0.00') ?>">
                </div>

                <!-- Notas -->
                <div class="col-12">
                    <label class="form-label fw-bold">Notas / Observaciones</label>
                    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Guardar Pago
                </button>
                <a href="<?= BASE_URL ?>lot-payments" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>