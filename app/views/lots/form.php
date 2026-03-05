<!-- app/views/lots/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-grid-3x3-<?= $mode === 'create' ? 'plus' : 'gear' ?> me-2"></i>
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
                        <!-- Manzana -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Manzana *</label>
                            <select name="block_id" class="form-select" required>
                                <option value="">-- Seleccionar manzana --</option>
                                <?php foreach ($blocks as $block): ?>
                                    <option value="<?= $block['id'] ?>"
                                        <?= ($data['block_id'] ?? 0) == $block['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($block['name']) ?> (<?= htmlspecialchars($block['project_title']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Número de lote -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Número de lote *</label>
                            <input type="text" name="lot_number" class="form-control" required
                                value="<?= htmlspecialchars($data['lot_number'] ?? '') ?>"
                                placeholder="Ej: 01, A-05, L-12">
                        </div>

                        <!-- Área -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Área (m²) *</label>
                            <input type="number" name="area" class="form-control" step="0.01" min="1" required
                                value="<?= htmlspecialchars($data['area'] ?? '') ?>">
                        </div>

                        <!-- Frente y fondo -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Frente (m)</label>
                            <input type="number" name="front" class="form-control" step="0.01" min="0"
                                value="<?= htmlspecialchars($data['front'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fondo (m)</label>
                            <input type="number" name="depth" class="form-control" step="0.01" min="0"
                                value="<?= htmlspecialchars($data['depth'] ?? '') ?>">
                        </div>

                        <!-- Precio -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio (S/) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required
                                value="<?= htmlspecialchars($data['price'] ?? '') ?>">
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado *</label>
                            <select name="status" class="form-select" required>
                                <option value="disponible" <?= ($data['status'] ?? 'disponible') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                                <option value="reservado" <?= ($data['status'] ?? '') === 'reservado' ? 'selected' : '' ?>>Reservado</option>
                                <option value="vendido" <?= ($data['status'] ?? '') === 'vendido' ? 'selected' : '' ?>>Vendido</option>
                                <option value="mora" <?= ($data['status'] ?? '') === 'mora' ? 'selected' : '' ?>>Mora</option>
                                <option value="cancelado" <?= ($data['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>

                        <!-- Características especiales -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Características especiales</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_corner" value="1"
                                            <?= !empty($data['is_corner']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Esquinero</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="faces_park" value="1"
                                            <?= !empty($data['faces_park']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Frente a parque</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="faces_main_street" value="1"
                                            <?= !empty($data['faces_main_street']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Frente a avenida principal</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="jiron_principal" value="1"
                                            <?= !empty($data['jiron_principal']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Jirón principal</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="calle_1" value="1"
                                            <?= !empty($data['calle_1']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Calle 1</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="calle_2" value="1"
                                            <?= !empty($data['calle_2']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Calle 2</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="pasaje_1_parque" value="1"
                                            <?= !empty($data['pasaje_1_parque']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Pasaje 1 (parque)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="pasaje_2" value="1"
                                            <?= !empty($data['pasaje_2']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Pasaje 2</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($mode === 'edit'): ?>
    <div class="row g-3 mt-4 border-top pt-4">
        <div class="col-12">
            <h5 class="mb-3">Posición en el mapa de la manzana (opcional)</h5>
            <p class="text-muted small mb-3">
                Estos valores se usan para posicionar el lote sobre el plano.  
                Los valores son en porcentaje (%) de la imagen de la manzana.
            </p>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Izquierda (left %)</label>
            <input type="number" name="map_left" class="form-control" step="0.01" min="0" max="100"
                   value="<?= htmlspecialchars($data['map_left'] ?? '0.00') ?>">
            <small class="text-muted">Distancia desde el borde izquierdo</small>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Superior (top %)</label>
            <input type="number" name="map_top" class="form-control" step="0.01" min="0" max="100"
                   value="<?= htmlspecialchars($data['map_top'] ?? '0.00') ?>">
            <small class="text-muted">Distancia desde el borde superior</small>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Ancho (%)</label>
            <input type="number" name="map_width" class="form-control" step="0.01" min="1" max="50"
                   value="<?= htmlspecialchars($data['map_width'] ?? '8.00') ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Alto (%)</label>
            <input type="number" name="map_height" class="form-control" step="0.01" min="1" max="50"
                   value="<?= htmlspecialchars($data['map_height'] ?? '8.00') ?>">
        </div>
    </div>
<?php endif; ?>

                        <!-- Notas -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Notas / Observaciones</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Detalles adicionales, ubicación exacta, restricciones..."><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>
                            <?= $mode === 'create' ? 'Crear Lote' : 'Guardar Cambios' ?>
                        </button>
                        <a href="<?= BASE_URL ?>lots" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>