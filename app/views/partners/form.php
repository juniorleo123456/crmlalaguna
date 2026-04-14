<!-- app/views/partners/form.php -->

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><?= htmlspecialchars($title) ?></h4>
    </div>
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <div class="row g-3">
                <!-- Usuario (solo en creación) -->
                <?php if ($mode === 'create'): ?>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Usuario (Socio) *</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Seleccionar usuario con rol socio --</option>
                            <?php foreach ($availableUsers as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Usuario</label>
                        <p class="form-control-static">
                            <?= htmlspecialchars($data['name'] ?? '') ?> 
                            (<?= htmlspecialchars($data['email'] ?? '') ?>)
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Comisión -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tasa de Comisión (%)</label>
                    <input type="number" name="commission_rate" class="form-control" step="0.01" min="0" 
                           value="<?= htmlspecialchars($data['commission_rate'] ?? '0.00') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipo de Comisión</label>
                    <select name="commission_type" class="form-select">
                        <option value="percent" <?= ($data['commission_type'] ?? 'percent') === 'percent' ? 'selected' : '' ?>>Porcentaje (%)</option>
                        <option value="fixed" <?= ($data['commission_type'] ?? 'percent')   === 'fixed' ? 'selected' : '' ?>>Monto fijo por venta</option>
                    </select>
                </div>

                <!-- Notas -->
                <div class="col-12">
                    <label class="form-label fw-bold">Notas / Observaciones</label>
                    <textarea name="notes" class="form-control" rows="4"><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i><?= $mode === 'create' ? 'Registrar Socio' : 'Guardar Cambios' ?>
                </button>
                <a href="<?= BASE_URL ?>partners" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>