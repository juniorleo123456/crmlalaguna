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
            default   => '¡Bienvenido al sistema!'
        };

        // Instanciar modelos
        $projectModel = new Project(getDBConnection());
        $clientModel  = new ClientModel(getDBConnection());
        $lotSaleModel = new LotSale(getDBConnection());

        // Conteos reales
        $activeProjects  = $projectModel->countActive();
        $totalClients    = $clientModel->countTotal();
        $pendingPayments = $lotSaleModel->countPendingPayments();
        $totalMora       = $lotSaleModel->getTotalMora();

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

        // Datos adicionales para admin
        $recentActivity = [];
        $upcomingPayments = [];
        $lotStatusStats = [];
        $activeProjects = 0;

        if ($user['role'] === 'admin') {
            // Actividad reciente: Últimas ventas, pagos y reservas
            $lotSalesModel = new LotSalesModel(getDBConnection());
            $lotPaymentsModel = new LotPaymentsModel(getDBConnection());
            $lotReservationsModel = new LotReservationsModel(getDBConnection());

            $recentSales = array_slice($lotSalesModel->getAll(), 0, 3);
            $recentPayments = array_slice($lotPaymentsModel->getAll(), 0, 3);
            $recentReservations = array_slice($lotReservationsModel->getAll(), 0, 3);

            // Combinar actividades
            foreach ($recentSales as $sale) {
                $recentActivity[] = [
                    'type' => 'Venta',
                    'text' => htmlspecialchars($sale['client_name']) . " - Lote " . htmlspecialchars($sale['lot_number']) . " (" . $sale['block_name'] . ")",
                    'date' => date('d/m/Y', strtotime($sale['sale_date'] ?? 'now')),
                    'amount' => 'S/ ' . number_format($sale['total_price'] ?? 0, 2)
                ];
            }

            foreach ($recentPayments as $payment) {
                $recentActivity[] = [
                    'type' => 'Pago',
                    'text' => htmlspecialchars($payment['client_name']) . " - " . htmlspecialchars($payment['payment_type']),
                    'date' => date('d/m/Y', strtotime($payment['payment_date'] ?? 'now')),
                    'amount' => 'S/ ' . number_format($payment['amount'] ?? 0, 2)
                ];
            }

            foreach ($recentReservations as $reservation) {
                $recentActivity[] = [
                    'type' => 'Reserva',
                    'text' => htmlspecialchars($reservation['client_name']) . " - Lote " . htmlspecialchars($reservation['lot_number']),
                    'date' => date('d/m/Y', strtotime($reservation['reservation_date'] ?? 'now')),
                    'amount' => ''
                ];
            }

            // Ordenar por fecha descendente y tomar los últimos 10
            usort($recentActivity, function ($a, $b) {
                $dateA = DateTime::createFromFormat('d/m/Y', $a['date'])->getTimestamp();
                $dateB = DateTime::createFromFormat('d/m/Y', $b['date'])->getTimestamp();
                return $dateB - $dateA;
            });
            $recentActivity = array_slice($recentActivity, 0, 10);

            // Pagos próximos en 30 días
            $upcomingPaymentsRaw = getDBConnection()->query("
                SELECT DISTINCT c.id, u.name AS client_name,
                       COALESCE(SUM(lp.amount), 0) AS total_amount,
                       MIN(lp.payment_date) AS next_due
                FROM lot_payments lp
                LEFT JOIN lot_sales ls ON lp.lot_sale_id = ls.id
                LEFT JOIN clients c ON ls.client_id = c.id
                LEFT JOIN users u ON c.user_id = u.id
                WHERE lp.payment_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                GROUP BY c.id, u.name
                ORDER BY lp.payment_date ASC
                LIMIT 10
            ")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($upcomingPaymentsRaw as $payment) {
                $upcomingPayments[] = [
                    'client' => htmlspecialchars($payment['client_name'] ?? 'Sin cliente'),
                    'amount' => 'S/ ' . number_format($payment['total_amount'] ?? 0, 2),
                    'date' => date('d/m/Y', strtotime($payment['next_due'] ?? 'now'))
                ];
            }

            // Distribución de estados de lotes
            $lotStatusQuery = getDBConnection()->query("
                SELECT status, COUNT(*) as count FROM lots GROUP BY status
            ")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($lotStatusQuery as $status) {
                $lotStatusStats[$status['status']] = $status['count'];
            }
        }

        // Renderizar la vista
        $this->render('dashboard/index', [
            'title'              => 'Dashboard - CRM La Laguna',
            'welcomeMessage'     => $welcomeMessage,
            'userRole'           => $user['role'],
            'userName'           => $user['name'],
            'stats'              => $stats,
            'recentActivity'     => $recentActivity,
            'upcomingPayments'   => $upcomingPayments,
            'lotStatusStats'     => $lotStatusStats,
            'activeProjects'     => $activeProjects
        ]);
    }
}
