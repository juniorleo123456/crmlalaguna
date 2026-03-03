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

<!-- Sección de actividad reciente (placeholder) -->
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Últimas actualizaciones</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Actualización: avance del proyecto "Residencial Azul" - 12% (por María)</li>
                <li class="list-group-item">Pago registrado: Cliente 'González' $4,500</li>
                <li class="list-group-item">Contrato subido: Proyecto 'Vista Mar'</li>
            </ul>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Próximos vencimientos</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Cliente: Pérez — $1,200 — 15/03/2026</li>
                <li class="list-group-item">Cliente: Ruiz — $900 — 20/03/2026</li>
            </ul>
        </div>
    </div>
    <!-- Gráfico de estado de proyectos -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Estado de proyectos</h5>
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
        type: 'bar',
        data: {
            labels: ['Planificación', 'Ejecución', 'Entregado', 'Cancelado'],
            datasets: [{
                label: 'Proyectos',
                data: [0, <?= $activeProjects ?>, 0, 0], // más adelante datos reales por estado
                backgroundColor: ['#6c757d', '#0d6efd', '#198754', '#dc3545'],
                borderColor: ['#5c636a', '#0b5ed7', '#157347', '#bb2d3b'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
</div>