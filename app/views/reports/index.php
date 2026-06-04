<!-- app/views/reports/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-file-earmark-bar-graph me-2"></i>
        Centro de Reportes
    </h2>
</div>

<div class="alert alert-info">
    Selecciona un reporte para ver datos detallados de tu sistema. Puedes filtrar y descargar en PDF.
</div>

<div class="row g-4">
    <!-- Reporte de Ventas -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100 border-primary">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <i class="bi bi-shop me-2"></i>Reporte de Ventas
                </h5>
                <p class="card-text text-muted">
                    Visualiza todas las ventas de lotes con detalles de cliente, monto y estado de pago.
                </p>
                <a href="<?= BASE_URL ?>reports/sales" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Reporte de Pagos -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100 border-success">
            <div class="card-body">
                <h5 class="card-title text-success">
                    <i class="bi bi-cash-coin me-2"></i>Reporte de Pagos
                </h5>
                <p class="card-text text-muted">
                    Analiza los pagos recibidos de clientes con detalles de monto, tipo y fecha.
                </p>
                <a href="<?= BASE_URL ?>reports/payments" class="btn btn-success btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Reporte de Lotes -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100 border-info">
            <div class="card-body">
                <h5 class="card-title text-info">
                    <i class="bi bi-map me-2"></i>Reporte de Lotes
                </h5>
                <p class="card-text text-muted">
                    Consulta el estado y disponibilidad de todos los lotes por proyecto.
                </p>
                <a href="<?= BASE_URL ?>reports/lots" class="btn btn-info btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>
</div>

<hr class="my-5">

<div class="row">
    <div class="col-12">
        <h5 class="mb-3">Información de Reportes</h5>
        <div class="list-group">
            <div class="list-group-item">
                <h6 class="mb-1">📊 Reporte de Ventas</h6>
                <p class="mb-0 text-muted small">Incluye: fecha, cliente, lote, proyecto, monto total, pago inicial, balance pendiente y estado de pago.</p>
            </div>
            <div class="list-group-item">
                <h6 class="mb-1">💰 Reporte de Pagos</h6>
                <p class="mb-0 text-muted small">Incluye: fecha de pago, cliente, monto, tipo de pago, estado y quién registró el pago.</p>
            </div>
            <div class="list-group-item">
                <h6 class="mb-1">🏘️ Reporte de Lotes</h6>
                <p class="mb-0 text-muted small">Incluye: número de lote, proyecto, bloque, área, estado (disponible/vendido/reservado), cliente y precio.</p>
            </div>
        </div>
    </div>
</div>
