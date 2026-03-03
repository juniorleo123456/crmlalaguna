<?php
// app/controllers/DashboardController.php

class DashboardController extends Controller
{
    public function index()
    {
        // Proteger la ruta: solo usuarios autenticados y con sesión válida
        $this->requireLogin();

        // Datos básicos del usuario (desde sesión)
        $user = [
            'name' => $_SESSION['name'] ?? 'Usuario',
            'role' => $_SESSION['role'] ?? 'desconocido'
        ];

        // Mensaje de bienvenida personalizado según rol
        $welcomeMessage = match ($user['role']) {
            'admin'   => "¡Bienvenido al panel de administración, {$user['name']}! Gestiona clientes, proyectos y socios.",
            'socio'   => "¡Hola {$user['name']}! Aquí tienes el resumen de tus servicios y comisiones.",
            'cliente' => "¡Bienvenido a tu área personal, {$user['name']}! Revisa tus pagos y estado de tu lote.",
            default   => "¡Bienvenido al sistema!"
        };

        // Estadísticas básicas (simuladas aquí, en producción vendrían de la base de datos)
        // Instanciar modelos
        $projectModel = new Project(getDBConnection());
        $clientModel  = new ClientModel(getDBConnection());
        $lotSaleModel = new LotSale(getDBConnection());

        // Conteos reales
        $activeProjects   = $projectModel->countActive();
        $totalClients     = $clientModel->countTotal();
        $pendingPayments  = $lotSaleModel->countPendingPayments();
        $totalMora        = $lotSaleModel->getTotalMora();

        // Tarjetas por rol (usando datos reales donde aplique)
        $stats = match ($user['role']) {
            'admin' => [
                ['title' => 'Proyectos activos',     'value' => $activeProjects,      'icon' => 'bi bi-grid-3x3',      'color' => 'primary'],
                ['title' => 'Clientes registrados',  'value' => $totalClients,        'icon' => 'bi bi-people',         'color' => 'success'],
                ['title' => 'Pagos pendientes',      'value' => $pendingPayments,      'icon' => 'bi bi-cash-coin',      'color' => 'danger'],
                ['title' => 'Mora total',            'value' => '$' . number_format($totalMora, 2), 'icon' => 'bi bi-exclamation-triangle', 'color' => 'warning']
            ],
            'socio' => [
                ['title' => 'Servicios asignados',   'value' => 9,   'icon' => 'bi bi-briefcase',      'color' => 'primary'], // placeholder, luego real
                ['title' => 'Comisiones este mes',   'value' => '$4,800', 'icon' => 'bi bi-currency-dollar', 'color' => 'success'],
                ['title' => 'Clientes activos',      'value' => $totalClients, 'icon' => 'bi bi-person-check',   'color' => 'info'],
                ['title' => 'Reportes pendientes',   'value' => 4,   'icon' => 'bi bi-file-earmark',   'color' => 'warning']
            ],
            'cliente' => [
                ['title' => 'Lotes reservados',      'value' => 1,   'icon' => 'bi bi-pin-map',        'color' => 'primary'],
                ['title' => 'Cuotas pagadas',        'value' => 7,   'icon' => 'bi bi-check-circle',   'color' => 'success'],
                ['title' => 'Próxima cuota',         'value' => '$450', 'icon' => 'bi bi-calendar-event', 'color' => 'info'],
                ['title' => 'Saldo pendiente',       'value' => '$3,600', 'icon' => 'bi bi-wallet2',      'color' => 'danger']
            ],
            default => []
        };
        // Renderizar la vista
        $this->render('dashboard/index', [
            'title'          => 'Dashboard - CRM La Laguna',
            'welcomeMessage' => $welcomeMessage,
            'userRole'       => $user['role'],
            'userName'       => $user['name'],
            'stats'          => $stats
        ]);
    }
}
