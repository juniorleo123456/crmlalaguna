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

                <!-- Resultado calculado en tiempo real -->
<div class="col-12 mt-4">
    <div class="alert alert-info" id="interestPreview" style="display: none;">
        <h5 class="mb-2">Proyección con intereses</h5>
        <p><strong>Total a pagar (con intereses):</strong> <span id="totalWithInterest">S/ 0.00</span></p>
        <p><strong>Cuota mensual aproximada:</strong> <span id="monthlyPayment">S/ 0.00</span></p>
        <small class="text-muted">Cálculo aproximado con interés simple anual. No incluye mora ni descuentos.</small>
    </div>
</div>

<!-- Configuración de vencimientos y mora (solo visible en edición o colapsado) -->
<div class="col-12 mt-4">
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#moraConfig" aria-expanded="false">
        Configuración de cuotas y mora (opcional)
    </button>
    <div class="collapse mt-3" id="moraConfig">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Día del mes de vencimiento</label>
                <input type="number" name="due_day_of_month" class="form-control" min="1" max="31"
                       value="<?= htmlspecialchars($data['due_day_of_month'] ?? '7') ?>">
                <small class="text-muted">Ej: 7 = vence cada día 7</small>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Días de gracia</label>
                <input type="number" name="grace_days" class="form-control" min="0"
                       value="<?= htmlspecialchars($data['grace_days'] ?? '7') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Mora (% por cuota vencida)</label>
                <input type="number" name="late_fee_rate" class="form-control" step="0.01" min="0"
                       value="<?= htmlspecialchars($data['late_fee_rate'] ?? '10.00') ?>">
            </div>
        </div>
    </div>
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

            <script>
document.addEventListener('DOMContentLoaded', function () {
    const totalInput = document.querySelector('input[name="total_price"]');
    const rateInput = document.querySelector('input[name="interest_rate"]');
    const termInput = document.querySelector('input[name="payment_term"]');
    const preview = document.getElementById('interestPreview');
    const totalSpan = document.getElementById('totalWithInterest');
    const monthlySpan = document.getElementById('monthlyPayment');

    function updatePreview() {
        const total = parseFloat(totalInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const months = parseInt(termInput.value) || 0;

        if (total <= 0 || rate <= 0 || months <= 0) {
            preview.style.display = 'none';
            return;
        }

        const interest = total * (rate / 100) * years;
        const totalWithInterest = total + interest;
        const monthly = totalWithInterest / months;

        totalSpan.textContent = 'S/ ' + totalWithInterest.toFixed(2);
        monthlySpan.textContent = 'S/ ' + monthly.toFixed(2);
        preview.style.display = 'block';
    }

    totalInput.addEventListener('input', updatePreview);
    rateInput.addEventListener('input', updatePreview);
    termInput.addEventListener('input', updatePreview);

    // Inicial
    updatePreview();
});
</script>
        </form>
    </div>
</div>