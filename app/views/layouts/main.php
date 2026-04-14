<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= $title ?? 'CRM La Laguna' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tu CSS custom -->
    <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet">

    <?php if (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<?php endif; ?>

    <!-- Estilos adicionales para sidebar responsive -->
    <style>
        #sidebar-wrapper {
            min-width: 250px;
            max-width: 250px;
            transition: margin-left 0.3s ease;
        }
        #page-content-wrapper {
            transition: margin-left 0.3s ease;
        }
        @media (max-width: 991.98px) {
    #sidebar-wrapper {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        z-index: 1050;
        transition: left 0.3s ease;
        overflow-y: auto;
        background: white;
        box-shadow: 2px 0 10px rgba(0,0,0,0.15);
    }
    #wrapper.toggled #sidebar-wrapper {
        left: 0;
    }
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
        transition: opacity 0.3s ease;
    }
    #wrapper.toggled .sidebar-overlay {
        display: block;
    }
    #page-content-wrapper {
        transition: none; /* ya no empujamos */
    }
}
        @media (min-width: 992px) {
            #sidebar-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-light">

    <!-- Mensajes flash -->
    <?php if ($flash = $this->getFlash()): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show m-3 position-fixed top-0 start-50 translate-middle-x" role="alert" style="z-index: 1100; min-width: 300px;">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <div class="bg-white border-end shadow-sm" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light p-3 text-center">
                    <img src="<?= BASE_URL ?>assets/img/logo-lalaguna.svg" alt="Logo" class="logo mb-2" style="max-width: 140px;">
                    <h5 class="mb-0">CRM La Laguna</h5>
                </div>

                <div class="list-group list-group-flush">
    <?php
    $allMenus = $this->getMenusForCurrentUser();

            // 1. Recolectar TODOS los padres activos primero
            $parents = [];
            foreach ($allMenus as $menu) {
                if ($menu['parent_id'] === null && ($menu['is_active'] ?? 1) == 1) {
                    $parents[$menu['id']] = $menu + ['children' => []];
                }
            }

            // 2. Asignar TODOS los hijos a sus padres (segunda pasada completa)
            foreach ($allMenus as $menu) {
                if ($menu['parent_id'] !== null && ($menu['is_active'] ?? 1) == 1) {
                    $parentId = (int)$menu['parent_id'];
                    if (isset($parents[$parentId])) {
                        $parents[$parentId]['children'][] = $menu;
                    }
                }
            }

            // 3. Ordenar padres por 'order'
            usort($parents, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

            // 4. Renderizar
            foreach ($parents as $parent):
                $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $parentPath  = '/' . trim($parent['url'] ?? '', '/');
                $isActive    = ($currentPath === $parentPath || strpos($currentPath, $parentPath . '/') === 0);
                $hasChildren = !empty($parent['children']);

                // Verificar roles del padre (seguro)
                $allowedRoles = json_decode($parent['roles'] ?? '[]', true);
                if (!is_array($allowedRoles)) {
                    $allowedRoles = [];
                }
                if (!in_array($_SESSION['role'] ?? 'guest', $allowedRoles)) {
                    continue;
                }
                ?>
        <a href="<?= BASE_URL . trim($parent['url'] ?? '#', '/') ?>" 
           class="list-group-item list-group-item-action py-3 <?= $isActive ? 'active' : '' ?> parent-menu"
           <?= $hasChildren ? 'data-bs-toggle="collapse" data-bs-target="#submenu-' . $parent['id'] . '" aria-expanded="' . ($isActive ? 'true' : 'false') . '" aria-controls="submenu-' . $parent['id'] . '"' : '' ?>>
            <?php if (!empty($parent['icon'])): ?>
                <i class="<?= htmlspecialchars($parent['icon']) ?> me-2"></i>
            <?php endif; ?>
            <?= htmlspecialchars($parent['label'] ?? 'Sin nombre') ?>
            <?php if ($hasChildren): ?>
                <i class="bi bi-chevron-down float-end submenu-icon <?= $isActive ? 'bi-chevron-up' : '' ?>"></i>
            <?php endif; ?>
        </a>

        <?php if ($hasChildren): ?>
            <div class="collapse <?= $isActive ? 'show' : '' ?>" id="submenu-<?= $parent['id'] ?>">
                <?php
                            // Ordenar hijos por 'order'
                            usort($parent['children'], fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

            foreach ($parent['children'] as $child):
                $childPath   = '/' . trim($child['url'] ?? '#', '/');
                $childActive = ($currentPath === $childPath || strpos($currentPath, $childPath . '/') === 0);

                // Verificar roles del hijo
                $childRoles = json_decode($child['roles'] ?? '[]', true);
                if (!is_array($childRoles)) {
                    $childRoles = [];
                }
                if (!in_array($_SESSION['role'] ?? 'guest', $childRoles)) {
                    continue;
                }
                ?>
                    <a href="<?= BASE_URL . trim($child['url'] ?? '#', '/') ?>" 
                       class="list-group-item list-group-item-action py-2 ps-5 <?= $childActive ? 'active' : '' ?>">
                        <?php if (!empty($child['icon'])): ?>
                            <i class="<?= htmlspecialchars($child['icon']) ?> me-2"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($child['label'] ?? 'Sin nombre') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Salir -->
    <a href="<?= BASE_URL ?>logout" class="list-group-item list-group-item-action py-3 text-danger border-top">
        <i class="bi bi-box-arrow-right me-2"></i> Salir
    </a>
</div>

            </div>
        <?php endif; ?>

        <!-- Overlay para cerrar sidebar en móvil -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Contenido principal -->
        <div id="page-content-wrapper" class="flex-grow-1 d-flex flex-column min-vh-100">
            <!-- Navbar superior -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <!-- Botón hamburguesa (solo móvil) -->
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                    <?php endif; ?>

                    <div class="ms-auto d-flex align-items-center">
                        <?php if (isset($_SESSION['name'])): ?>
                            <div class="me-3 text-end d-none d-md-block">
                                <small class="text-muted">Bienvenido</small>
                                <div class="fw-bold"><?= htmlspecialchars($_SESSION['name']) ?></div>
                            </div>
                            <img src="<?= BASE_URL ?>assets/img/logo-lalaguna.svg" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="flex-grow-1 container-fluid py-4">
                <?= $content ?>
            </main>

            <!-- Footer -->
            <footer class="bg-light text-center py-3 border-top mt-auto">
                <small class="text-muted">
                    CRM La Laguna © <?= date('Y') ?> – Desarrollado con MVC PHP
                </small>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>

    <!-- Toggle sidebar -->
     <script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle submenús SIN bloquear la navegación del padre
    document.querySelectorAll('.parent-menu[data-bs-toggle="collapse"]').forEach(item => {
        item.addEventListener('click', function(e) {
            // Permitimos que el href se ejecute normalmente (redirigir)
            // Solo manejamos el toggle del submenú si tiene hijos
            const targetId = this.getAttribute('data-bs-target');
            if (targetId) {
                const collapse = document.querySelector(targetId);
                if (collapse) {
                    collapse.classList.toggle('show');
                    // Cambiar ícono (opcional: puedes quitarlo si no te gusta)
                    const icon = this.querySelector('.submenu-icon');
                    if (icon) {
                        icon.classList.toggle('bi-chevron-down');
                        icon.classList.toggle('bi-chevron-up');
                    }
                }
            }
            // NO usamos e.preventDefault() → el enlace SIEMPRE redirige
        });
    });

    // Toggle sidebar en móvil (ya lo tenías)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const wrapper = document.getElementById('wrapper');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && overlay) {
        sidebarToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            wrapper.classList.toggle('toggled');
        });

        overlay.addEventListener('click', function () {
            wrapper.classList.remove('toggled');
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && wrapper.classList.contains('toggled')) {
                wrapper.classList.remove('toggled');
            }
        });
    }
});
</script>
</body>
</html>