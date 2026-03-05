<!-- app/views/lots/map.php -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-map me-2"></i>
        Mapa de Lotes
    </h2>
</div>

<!-- Filtro por manzana -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Seleccionar manzana</label>
                <select name="block_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($blocks as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= $block_id == $b['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['name']) ?> (<?= htmlspecialchars($b['project_title']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php if (!$block): ?>
    <div class="alert alert-info">
        Selecciona una manzana para ver su plano.
    </div>
<?php elseif (empty($block['plano_url'])): ?>
    <div class="alert alert-warning">
        Esta manzana no tiene plano cargado.
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <div style="position: relative; width: 100%; height: auto; overflow: auto;">
                <img src="<?= BASE_URL . $block['plano_url'] ?>" alt="Plano de <?= htmlspecialchars($block['name']) ?>" style="max-width: 100%; height: auto;">

                <!-- Overlays para cada lote -->
                <?php foreach ($lots as $lot): ?>
                    <?php
                    $color = match ($lot['status']) {
                        'disponible' => 'rgba(40, 167, 69, 0.6)',
                        'reservado'  => 'rgba(255, 193, 7, 0.6)',
                        'vendido'    => 'rgba(0, 123, 255, 0.6)',
                        'mora'       => 'rgba(220, 53, 69, 0.6)',
                        'cancelado'  => 'rgba(108, 117, 125, 0.6)',
                        default      => 'rgba(248, 249, 250, 0.6)'
                    };
                    ?>
                    <div style="position: absolute; left: <?= $lot['map_left'] ?>%; top: <?= $lot['map_top'] ?>%; width: <?= $lot['map_width'] ?>%; height: <?= $lot['map_height'] ?>%; background-color: <?= $color ?>; border: 1px solid #fff; cursor: pointer;"
                        title="Lote: <?= htmlspecialchars($lot['lot_number']) ?> - Área: <?= number_format($lot['area'], 2) ?> m² - Precio: S/ <?= number_format($lot['price'], 2) ?> - Estado: <?= ucfirst($lot['status']) ?>">
                        <a href="<?= BASE_URL ?>lots/edit/<?= $lot['id'] ?>" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold; text-decoration: none;">
                            <?= htmlspecialchars($lot['lot_number']) ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>