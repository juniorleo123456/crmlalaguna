<!-- app/views/clients/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person-<?= $mode === 'create' ? 'plus' : 'gear' ?> me-2"></i>
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
                        <!-- Datos personales -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre completo *</label>
                            <input type="text" name="name" class="form-control" required
                                value="<?= htmlspecialchars($data['name'] ?? '') ?>"
                                placeholder="Ej: Juan Pérez López">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email *</label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                                placeholder="ejemplo@dominio.com">
                        </div>

                        <?php if ($mode === 'create'): ?>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Contraseña inicial *</label>
                                <input type="password" name="password" class="form-control" required minlength="6"
                                    placeholder="Mínimo 6 caracteres">
                                <small class="text-muted">El cliente podrá cambiarla después.</small>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono</label>
                            <input type="tel" name="phone" class="form-control"
                                value="<?= htmlspecialchars($data['phone'] ?? '') ?>"
                                placeholder="Ej: +51 987 654 321">
                        </div>

                        <!-- Dirección -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Dirección completa</label>
                            <input type="text" name="address" class="form-control"
                                value="<?= htmlspecialchars($data['address'] ?? '') ?>"
                                placeholder="Calle, número, distrito...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Ciudad</label>
                            <input type="text" name="city" class="form-control"
                                value="<?= htmlspecialchars($data['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Departamento</label>
                            <input type="text" name="state" class="form-control"
                                value="<?= htmlspecialchars($data['state'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Código Postal</label>
                            <input type="text" name="postal_code" class="form-control"
                                value="<?= htmlspecialchars($data['postal_code'] ?? '') ?>">
                        </div>

                        <!-- Empresa / Negocio -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre de la empresa</label>
                            <input type="text" name="company_name" class="form-control"
                                value="<?= htmlspecialchars($data['company_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">RUC / DNI</label>
                            <input type="text" name="tax_id" class="form-control"
                                value="<?= htmlspecialchars($data['tax_id'] ?? '') ?>">
                        </div>

                        <!-- Notas -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Notas / Observaciones</label>
                            <textarea name="notes" class="form-control" rows="4"
                                placeholder="Información adicional, preferencias, historial..."><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>
                            <?= $mode === 'create' ? 'Crear Cliente' : 'Guardar Cambios' ?>
                        </button>
                        <a href="<?= BASE_URL ?>clients" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>