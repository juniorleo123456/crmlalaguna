<!-- app/views/dashboard/index.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Dashboard</h2>
    <div>
        <?php if ($userRole === 'admin'): ?>
            <a href="<?= BASE_URL ?>clients/create" class="btn btn-success btn-sm me-2">Crear Cliente</a>
            <a href="<?= BASE_URL ?>projects/create" class="btn btn-primary btn-sm">Nuevo Proyecto</a>
        <?php endif; ?>
    </div>
</div> 

<div class="alert alert-info">
    <?= htmlspecialchars($welcomeMessage) ?>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row g-3 mb-4">
    <?php foreach ($stats as $stat): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card border-<?= $stat['color'] ?> shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted"><?= htmlspecialchars($stat['title']) ?></h6>
                    <h3 class="text-<?= $stat['color'] ?>"><?= htmlspecialchars($stat['value']) ?></h3>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Sección de actividad reciente (datos reales) -->
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Últimas actualizaciones</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($recentActivity)): ?>
                    <?php foreach ($recentActivity as $activity): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($activity['type']) ?>:</strong>
                            <?= htmlspecialchars($activity['text']) ?>
                            <span class="text-muted float-end">
                                <?= htmlspecialchars($activity['date']) ?>
                                <?php if (!empty($activity['amount'])): ?>
                                    — <?= htmlspecialchars($activity['amount']) ?>
                                <?php endif; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">No hay actividad registrada.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Próximos vencimientos (30 días)</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($upcomingPayments)): ?>
                    <?php foreach ($upcomingPayments as $payment): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($payment['client']) ?></strong> —
                            <?= htmlspecialchars($payment['amount']) ?> —
                            <span class="text-muted"><?= htmlspecialchars($payment['date']) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">No hay pagos próximos registrados.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <!-- Gráfico de estado de proyectos -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Estado de lotes</h5>
            </div>
            <div class="card-body">
                <canvas id="projectsChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('projectsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Disponibles', 'Vendidos', 'Reservados', 'En Mora', 'Cancelados'],
            datasets: [{
                label: 'Lotes',
                data: [
                    <?= $lotStatusStats['disponible'] ?? 0 ?>,
                    <?= $lotStatusStats['vendido'] ?? 0 ?>,
                    <?= $lotStatusStats['reservado'] ?? 0 ?>,
                    <?= $lotStatusStats['mora'] ?? 0 ?>,
                    <?= $lotStatusStats['cancelado'] ?? 0 ?>
                ],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                borderColor: ['#0b5ed7', '#157347', '#ffb81c', '#bb2d3b', '#5c636a'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
</div>