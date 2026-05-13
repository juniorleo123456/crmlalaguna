<!-- app/views/contracts/form.php -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= htmlspecialchars($title) ?></h4>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Cliente *</label>
                            <select name="client_id" class="form-select" required>
                                <option value="">-- Seleccionar cliente --</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>">
                                        <?= htmlspecialchars($client['name']) ?> 
                                        (<?= htmlspecialchars($client['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Tipo de Contrato *</label>
                            <select name="contract_type" class="form-select" required>
                                <option value="">-- Seleccionar tipo --</option>
                                <option value="compra-venta-futuro">Contrato de Compra-Venta a Futuro</option>
                                <option value="titulo-propiedad">Título de Propiedad</option>
                                <option value="adenda">Adenda</option>
                                <option value="otros">Otros Documentos</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Archivo (PDF u otros) *</label>
                            <input type="file" name="contract_file" class="form-control" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Firma (opcional)</label>
                            <input type="date" name="signed_date" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción / Notas</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Detalles adicionales sobre el contrato..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-upload me-2"></i>Subir Contrato
                        </button>
                        <a href="<?= BASE_URL ?>contracts" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>