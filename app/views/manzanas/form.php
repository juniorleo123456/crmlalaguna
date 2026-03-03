<!-- app/views/manzanas/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-grid-3x3-gap-<?= $mode === 'create' ? 'plus' : 'gear' ?> me-2"></i>
                    <?= $title ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <?php if ($mode === 'edit'): ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <!-- Proyecto asociado -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Proyecto asociado *</label>
                            <select name="project_id" class="form-select" required>
                                <option value="">-- Seleccionar proyecto --</option>
                                <!-- Asume que pasas $projectsList desde el controlador -->
                                <?php foreach ($projectsList as $project): ?>
                                    <option value="<?= $project['id'] ?>"
                                        <?= ($data['project_id'] ?? 0) == $project['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($project['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nombre de la manzana -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre de la manzana *</label>
                            <input type="text" name="name" class="form-control" required
                                value="<?= htmlspecialchars($data['name'] ?? '') ?>"
                                placeholder="Ej: A, B, C, 1, 2...">
                        </div>

                        <!-- Total lotes -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Total de lotes *</label>
                            <input type="number" name="total_lots" class="form-control" required min="1"
                                value="<?= htmlspecialchars($data['total_lots'] ?? 0) ?>">
                        </div>

                        <!-- Pago mensual mínimo -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pago mensual mínimo (S/)</label>
                            <input type="number" name="min_monthly_payment" class="form-control" step="0.01" min="0"
                                value="<?= htmlspecialchars($data['min_monthly_payment'] ?? 0) ?>">
                        </div>

                        <!-- Cuota inicial -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cuota inicial mínima (S/)</label>
                            <input type="number" name="initial_payment" class="form-control" step="0.01" min="0"
                                value="<?= htmlspecialchars($data['initial_payment'] ?? 0) ?>">
                        </div>

                        <!-- Descripción -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Detalles de la manzana, ubicación dentro del proyecto..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>
                            <?= $mode === 'create' ? 'Crear Manzana' : 'Guardar Cambios' ?>
                        </button>
                        <a href="<?= BASE_URL ?>blocks" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>