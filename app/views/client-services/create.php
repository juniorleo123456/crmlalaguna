<!-- app/views/client-services/create.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-plus-circle me-2"></i>
        Asociar Proyecto a <?= htmlspecialchars($client['name']) ?>
    </h2>
    <a href="<?= BASE_URL ?>clients/view/<?= $client['id'] ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Cancelar
    </a>
</div>

<?php if (empty($projects)): ?>
    <div class="alert alert-warning text-center py-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        No hay proyectos disponibles para asociar.
        <br><a href="<?= BASE_URL ?>projects/create" class="text-primary">Crear un proyecto primero</a>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                <div class="mb-4">
                    <label class="form-label fw-bold">Selecciona un proyecto *</label>
                    <select name="project_id" class="form-select" required>
                        <option value="">-- Elige un proyecto --</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['id'] ?>">
                                <?= htmlspecialchars($project['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-link-45deg me-2"></i>Asociar Proyecto
                    </button>
                    <a href="<?= BASE_URL ?>clients/view/<?= $client['id'] ?>" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>