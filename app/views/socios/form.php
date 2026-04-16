<!-- app/views/socios/form.php -->

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-person-<?= $mode === 'create' ? 'plus' : 'gear' ?> me-2"></i>
            <?= htmlspecialchars($title) ?>
        </h4>
    </div>
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <div class="row g-3">

                <?php if ($mode === 'create'): ?>
                    <!-- Seleccionar usuario (solo en creación) -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Usuario con rol Socio *</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Seleccionar usuario --</option>
                            <?php foreach ($availableUsers as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['name']) ?> — <?= htmlspecialchars($user['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <!-- Modo edición: mostrar datos del usuario -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Usuario</label>
                        <p class="form-control-static fw-bold">
                            <?= htmlspecialchars($data['name'] ?? '') ?> 
                            (<?= htmlspecialchars($data['email'] ?? '') ?>)
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Datos adicionales del socio -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre de la Empresa</label>
                    <input type="text" name="nombre_empresa" class="form-control"
                           value="<?= htmlspecialchars($data['nombre_empresa'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Teléfono adicional</label>
                    <input type="tel" name="telefono_extra" class="form-control"
                           value="<?= htmlspecialchars($data['telefono_extra'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="<?= htmlspecialchars($data['direccion'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Ciudad</label>
                    <input type="text" name="ciudad" class="form-control"
                           value="<?= htmlspecialchars($data['ciudad'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Banco</label>
                    <input type="text" name="banco" class="form-control"
                           value="<?= htmlspecialchars($data['banco'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Número de Cuenta</label>
                    <input type="text" name="cuenta_bancaria" class="form-control"
                           value="<?= htmlspecialchars($data['cuenta_bancaria'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipo de Cuenta</label>
                    <input type="text" name="tipo_cuenta" class="form-control"
                           value="<?= htmlspecialchars($data['tipo_cuenta'] ?? '') ?>">
                </div>

                <!-- Notas -->
                <div class="col-12">
                    <label class="form-label fw-bold">Notas / Observaciones</label>
                    <textarea name="notes" class="form-control" rows="4"
                        placeholder="Información adicional sobre el socio..."><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-5 d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-2"></i>
                    <?= $mode === 'create' ? 'Registrar Socio' : 'Guardar Cambios' ?>
                </button>
                <a href="<?= BASE_URL ?>socios" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>