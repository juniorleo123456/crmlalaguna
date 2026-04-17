<!-- app/views/partner_payments/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= htmlspecialchars($title) ?></h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <?php if ($mode === 'edit'): ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Socio *</label>
                            <select name="socio_id" class="form-select" required>
                                <option value="">-- Seleccionar socio --</option>
                                <?php foreach ($socios as $socio): ?>
                                    <option value="<?= $socio['id'] ?>" <?= ($data['socio_id'] ?? 0) == $socio['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($socio['name']) ?> 
                                        (<?= htmlspecialchars($socio['nombre_empresa'] ?? '-') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Período (Mes) *</label>
                            <input type="month" name="periodo" class="form-control" required
                                   value="<?= htmlspecialchars($data['periodo'] ?? date('Y-m')) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Total Ingresos del Mes (S/) *</label>
                            <input type="number" id="totalIngresos" name="total_ingresos_mes" 
                                   class="form-control" step="0.01" min="0" required
                                   value="<?= htmlspecialchars($data['total_ingresos_mes'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Comisión</label>
                            <select name="tipo_comision" id="tipoComision" class="form-select">
                                <option value="percent" <?= ($data['tipo_comision'] ?? 'percent') === 'percent' ? 'selected' : '' ?>>Porcentaje (%)</option>
                                <option value="fixed" <?= ($data['tipo_comision'] ?? 'percent') === 'fixed' ? 'selected' : '' ?>>Monto Fijo</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="campoPorcentaje">
                            <label class="form-label fw-bold">Porcentaje (%)</label>
                            <input type="number" id="porcentaje" name="porcentaje" class="form-control" step="0.01" min="0"
                                   value="<?= htmlspecialchars($data['porcentaje'] ?? '') ?>">
                        </div>

                        <div class="col-md-6" id="campoMontoFijo" style="display: none;">
                            <label class="form-label fw-bold">Monto Fijo (S/)</label>
                            <input type="number" id="montoFijo" name="monto_fijo" class="form-control" step="0.01" min="0"
                                   value="<?= htmlspecialchars($data['monto_fijo'] ?? '') ?>">
                        </div>

                        <!-- Monto final calculado -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Monto a Pagar al Socio (S/) *</label>
                            <input type="number" id="montoPago" name="monto_pago" class="form-control" step="0.01" min="0" required
                                   value="<?= htmlspecialchars($data['monto_pago'] ?? '') ?>">
                            <small id="previewMonto" class="text-success fw-bold"></small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Notas / Observaciones</label>
                            <textarea name="notes" class="form-control" rows="4"><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i><?= $mode === 'create' ? 'Registrar Pago' : 'Guardar Cambios' ?>
                        </button>
                        <a href="<?= BASE_URL ?>partners/comisiones" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalInput     = document.getElementById('totalIngresos');
    const tipoSelect     = document.getElementById('tipoComision');
    const percentInput   = document.getElementById('porcentaje');
    const fixedInput     = document.getElementById('montoFijo');
    const montoPagoInput = document.getElementById('montoPago');
    const preview        = document.getElementById('previewMonto');

    function calcularMonto() {
        const total = parseFloat(totalInput.value) || 0;
        let monto = 0;

        if (tipoSelect.value === 'percent') {
            const porcentaje = parseFloat(percentInput.value) || 0;
            monto = total * (porcentaje / 100);
            fixedInput.style.display = 'none';
            percentInput.parentElement.style.display = 'block';
        } else {
            monto = parseFloat(fixedInput.value) || 0;
            percentInput.parentElement.style.display = 'none';
            fixedInput.style.display = 'block';
        }

        montoPagoInput.value = monto.toFixed(2);
        preview.textContent = 'Monto calculado: S/ ' + monto.toFixed(2);
    }

    totalInput.addEventListener('input', calcularMonto);
    percentInput.addEventListener('input', calcularMonto);
    fixedInput.addEventListener('input', calcularMonto);
    tipoSelect.addEventListener('change', calcularMonto);

    // Calcular al cargar
    calcularMonto();
});
</script>