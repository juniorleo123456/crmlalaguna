<?php

class ReportsController extends Controller
{
    private LotSalesModel $saleModel;
    private LotPaymentsModel $paymentModel;
    private LotModel $lotModel;
    private ProjectModel $projectModel;
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden ver reportes.');
            $this->redirect('dashboard');
        }

        $this->saleModel = new LotSalesModel(getDBConnection());
        $this->paymentModel = new LotPaymentsModel(getDBConnection());
        $this->lotModel = new LotModel(getDBConnection());
        $this->projectModel = new ProjectModel(getDBConnection());
        $this->clientModel = new ClientModel(getDBConnection());
    }

    public function index()
    {
        $this->render('reports/index', [
            'title' => 'Centro de Reportes'
        ]);
    }

    public function sales()
    {
        $dateFrom = trim($_GET['date_from'] ?? '');
        $dateTo = trim($_GET['date_to'] ?? '');
        $project = (int) ($_GET['project'] ?? 0);
        $client = (int) ($_GET['client'] ?? 0);
        $status = trim($_GET['status'] ?? '');

        $sales = $this->getSalesData($dateFrom, $dateTo, $project, $client, $status);
        $projects = $this->projectModel->getAll();
        $clients = $this->clientModel->getAll();

        $this->render('reports/sales', [
            'title' => 'Reporte de Ventas',
            'sales' => $sales,
            'projects' => $projects,
            'clients' => $clients,
            'filters' => compact('dateFrom', 'dateTo', 'project', 'client', 'status')
        ]);
    }

    public function payments()
    {
        $dateFrom = trim($_GET['date_from'] ?? '');
        $dateTo = trim($_GET['date_to'] ?? '');
        $client = (int) ($_GET['client'] ?? 0);
        $status = trim($_GET['status'] ?? '');

        $payments = $this->getPaymentsData($dateFrom, $dateTo, $client, $status);
        $clients = $this->clientModel->getAll();
        $paymentTypes = ['reserva', 'inicial', 'cuota_fija', 'cuota_minima', 'adelanto', 'saldo_final', 'mora'];

        $this->render('reports/payments', [
            'title' => 'Reporte de Pagos',
            'payments' => $payments,
            'clients' => $clients,
            'paymentTypes' => $paymentTypes,
            'filters' => compact('dateFrom', 'dateTo', 'client', 'status')
        ]);
    }

    public function lots()
    {
        $project = (int) ($_GET['project'] ?? 0);
        $status = trim($_GET['status'] ?? '');

        $lots = $this->getLotsData($project, $status);
        $projects = $this->projectModel->getAll();
        $lotStatuses = ['disponible', 'vendido', 'reservado', 'mora', 'cancelado'];

        $this->render('reports/lots', [
            'title' => 'Reporte de Lotes',
            'lots' => $lots,
            'projects' => $projects,
            'lotStatuses' => $lotStatuses,
            'filters' => compact('project', 'status')
        ]);
    }

    // Query helpers
    private function getSalesData($dateFrom, $dateTo, $project, $client, $status)
    {
        $query = "
            SELECT ls.*,
                   l.lot_number, b.name AS block_name, p.title AS project_title,
                   u.name AS client_name
            FROM lot_sales ls
            LEFT JOIN lots l ON ls.lot_id = l.id
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            LEFT JOIN clients c ON ls.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE 1=1
        ";

        $params = [];

        if ($dateFrom) {
            $query .= " AND ls.sale_date >= ?";
            $params[] = $dateFrom;
        }

        if ($dateTo) {
            $query .= " AND ls.sale_date <= ?";
            $params[] = $dateTo;
        }

        if ($project > 0) {
            $query .= " AND p.id = ?";
            $params[] = $project;
        }

        if ($client > 0) {
            $query .= " AND ls.client_id = ?";
            $params[] = $client;
        }

        if ($status) {
            $query .= " AND ls.payment_status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY ls.sale_date DESC";

        $stmt = getDBConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPaymentsData($dateFrom, $dateTo, $client, $status)
    {
        $query = "
            SELECT lp.*,
                   ls.id AS sale_id, l.lot_number,
                   u.name AS client_name, registrar.name AS registered_by_name
            FROM lot_payments lp
            LEFT JOIN lot_sales ls ON lp.lot_sale_id = ls.id
            LEFT JOIN lots l ON ls.lot_id = l.id
            LEFT JOIN clients c ON ls.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            LEFT JOIN users registrar ON lp.registered_by = registrar.id
            WHERE 1=1
        ";

        $params = [];

        if ($dateFrom) {
            $query .= " AND lp.payment_date >= ?";
            $params[] = $dateFrom;
        }

        if ($dateTo) {
            $query .= " AND lp.payment_date <= ?";
            $params[] = $dateTo;
        }

        if ($client > 0) {
            $query .= " AND ls.client_id = ?";
            $params[] = $client;
        }

        if ($status) {
            $query .= " AND lp.payment_type = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY lp.payment_date DESC";

        $stmt = getDBConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getLotsData($project, $status)
    {
        $query = "
            SELECT l.*, b.name AS block_name, p.title AS project_title,
                   u.name AS client_name, ls.sale_date
            FROM lots l
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            LEFT JOIN lot_sales ls ON ls.lot_id = l.id
            LEFT JOIN clients c ON ls.client_id = c.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE 1=1
        ";

        $params = [];

        if ($project > 0) {
            $query .= " AND p.id = ?";
            $params[] = $project;
        }

        if ($status) {
            $query .= " AND l.status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY l.lot_number ASC";

        $stmt = getDBConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
