<!-- app/views/projects/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-building-<?= $mode === 'create' ? 'plus' : 'gear' ?> me-2"></i>
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
                        <!-- Información principal -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Título del proyecto *</label>
                            <input type="text" name="title" class="form-control" required
                                value="<?= htmlspecialchars($data['title'] ?? '') ?>"
                                placeholder="Ej: Residencial Los Jardines">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Detalles del proyecto, ubicación, características..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Agrega esto en su lugar (después de la descripción por ejemplo) -->
<div class="col-12 mb-4">
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Importante:</strong> Los clientes se asocian a este proyecto desde la ficha del cliente o desde la sección de servicios asociados al cliente.
    </div>
</div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado actual</label>
                            <select name="status" class="form-select" required>
                                <option value="planificacion" <?= ($data['status'] ?? 'planificacion') === 'planificacion' ? 'selected' : '' ?>>Planificación</option>
                                <option value="ejecucion" <?= ($data['status'] ?? '') === 'ejecucion' ? 'selected' : '' ?>>Ejecución</option>
                                <option value="entregado" <?= ($data['status'] ?? '') === 'entregado' ? 'selected' : '' ?>>Entregado</option>
                                <option value="cancelado" <?= ($data['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>

                        <!-- Fechas -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de inicio</label>
                            <input type="date" name="start_date" class="form-control"
                                value="<?= htmlspecialchars($data['start_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha estimada de entrega</label>
                            <input type="date" name="end_date" class="form-control"
                                value="<?= htmlspecialchars($data['end_date'] ?? '') ?>">
                        </div>

                        <!-- Progreso -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Progreso actual (%)</label>
                            <input type="number" name="progress" class="form-control" min="0" max="100"
                                value="<?= htmlspecialchars($data['progress'] ?? 0) ?>">
                            <small class="text-muted">Valor entre 0 y 100</small>
                        </div>
                    </div>

                    <!-- Al final del formulario, antes de los botones -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>
                            <?= $mode === 'create' ? 'Crear Proyecto' : 'Guardar Cambios' ?>
                        </button>
                        <a href="<?= BASE_URL ?>projects" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>