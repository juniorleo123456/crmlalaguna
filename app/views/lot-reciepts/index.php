<!-- app/views/lot-payments/receipts.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-receipt me-2"></i>
        Boletas y Comprobantes
    </h2>
</div>

<?php if (empty($receipts)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle-fill fs-1 me-2"></i>
        <h5>Aún no hay boletas registradas.</h5>
        <p>Registra pagos con comprobante para que aparezcan aquí.</p>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($receipts as $receipt): ?>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Boleta #<?= $receipt['receipt_number'] ?? 'Sin número' ?></h5>
                        <p class="card-text">
                            <strong>Cliente:</strong> <?= htmlspecialchars($receipt['client_name'] ?? 'N/A') ?><br>
                            <strong>Lote:</strong> <?= htmlspecialchars($receipt['lot_number'] ?? 'N/A') ?><br>
                            <strong>Fecha pago:</strong> <?= date('d/m/Y', strtotime($receipt['payment_date'])) ?><br>
                            <strong>Monto:</strong> S/ <?= number_format($receipt['amount'], 2) ?><br>
                            <strong>Tipo:</strong> <?= ucfirst(str_replace('_', ' ', $receipt['payment_type'])) ?>
                        </p>
                        <?php if ($receipt['receipt_file']): ?>
                            <a href="<?= BASE_URL . $receipt['receipt_file'] ?>"
                                class="btn btn-primary w-100 mt-2" target="_blank">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Ver / Descargar Boleta
                            </a>
                        <?php else: ?>
                            <p class="text-muted text-center mt-2">Sin archivo adjunto</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>