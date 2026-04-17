<!-- app/views/commissions/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-cash-stack me-2"></i>
                    <?= htmlspecialchars($title) ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                    <div class="row g-3">
                        <!-- Socio -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Socio *</label>
                            <select name="socio_id" class="form-select" required>
                                <option value="">-- Seleccionar socio --</option>
                                <?php foreach ($socios as $socio): ?>
                                    <option value="<?= $socio['id'] ?>">
                                        <?= htmlspecialchars($socio['name']) ?> 
                                        (<?= htmlspecialchars($socio['nombre_empresa'] ?? 'Sin empresa') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Lote asociado (opcional) -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Lote / Venta asociada (opcional)</label>
                            <select name="lot_sale_id" class="form-select">
                                <option value="">-- Ninguna venta específica --</option>
                                <!-- Aquí puedes cargar ventas si quieres, por ahora lo dejamos simple -->
                            </select>
                        </div>

                        <!-- Monto -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Monto de la Comisión (S/) *</label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0" required
                                   value="<?= htmlspecialchars($data['amount'] ?? '') ?>">
                        </div>

                        <!-- Fecha de pago -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Pago</label>
                            <input type="date" name="payment_date" class="form-control"
                                   value="<?= htmlspecialchars($data['payment_date'] ?? date('Y-m-d')) ?>">
                        </div>

                        <!-- Notas -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Notas / Observaciones</label>
                            <textarea name="notes" class="form-control" rows="4"
                                placeholder="Detalles del pago, factura, etc."><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>Registrar Comisión
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