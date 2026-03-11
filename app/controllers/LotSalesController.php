<?php
// app/controllers/LotSalesController.php

class LotSalesController extends Controller
{
    private LotSalesModel $saleModel;
    private LotModel $lotModel;
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido.');
            $this->redirect('dashboard');
        }

        $this->saleModel = new LotSalesModel(getDBConnection());
        $this->lotModel = new LotModel(getDBConnection());
        $this->clientModel = new ClientModel(getDBConnection());
    }

    public function index()
{
    $page = (int) ($_GET['page'] ?? 1);
    $perPage = 10;

    // Filtros
    $filters = [
        'payment_status' => trim($_GET['payment_status'] ?? ''),
        'client_id' => (int) ($_GET['client_id'] ?? 0),
        'search' => trim($_GET['search'] ?? '')
    ];

    $sales = $this->saleModel->getAllFiltered($page, $perPage, $filters);
    $total = $this->saleModel->countFiltered($filters);
    $totalPages = max(1, ceil($total / $perPage));

    // Datos para los filtros
    $clients = $this->clientModel->getAll();
    $statuses = ['al_dia', 'atrasado', 'mora', 'cancelado']; // según tu tabla lot_sales

    $this->render('lot-sales/index', [
        'title' => 'Listado de Ventas / Contratos',
        'sales' => $sales,
        'page' => $page,
        'totalPages' => $totalPages,
        'filters' => $filters,
        'clients' => $clients,
        'statuses' => $statuses
    ]);
}

    public function create()
    {
        $this->form('create');
    }

    public function edit($id)
    {
        $this->form('edit', (int) $id);
    }

    private function form(string $mode, int $id = 0)
    {
    $data = [];
    $title = $mode === 'create' ? 'Nueva Venta / Contrato' : 'Editar Venta';

    if ($mode === 'edit') {
        $data = $this->saleModel->getById($id);
        if (!$data) {
            $this->setFlash('danger', 'Venta no encontrada.');
            $this->redirect('lot-sales');
        }
    }

    $lots = $this->lotModel->getAll();     // Puedes filtrar solo 'disponible' o 'reservado'
    $clients = $this->clientModel->getAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            $this->setFlash('danger', 'Error de seguridad.');
            $this->redirect("lot-sales/{$mode}" . ($id ? "/$id" : ""));
        }

        $data = [
            'lot_id' => (int) ($_POST['lot_id'] ?? 0),
            'client_id' => (int) ($_POST['client_id'] ?? 0),
            'sale_date' => trim($_POST['sale_date'] ?? date('Y-m-d')),
            'total_price' => (float) ($_POST['total_price'] ?? 0),
            'initial_payment' => (float) ($_POST['initial_payment'] ?? 0),
            'balance' => (float) ($_POST['balance'] ?? 0),
            'payment_term' => (int) ($_POST['payment_term'] ?? 0),
            'interest_rate' => (float) ($_POST['interest_rate'] ?? 0),
            'monthly_fixed_payment' => (float) ($_POST['monthly_fixed_payment'] ?? 0),
            'monthly_min_payment' => (float) ($_POST['monthly_min_payment'] ?? 0),
            'discount_percent' => (float) ($_POST['discount_percent'] ?? 0),
            'final_payment_deadline' => trim($_POST['final_payment_deadline'] ?? null),
            'contract_file' => trim($_POST['contract_file'] ?? null),
            'notes' => trim($_POST['notes'] ?? '')
        ];

        $errors = [];
        if ($data['lot_id'] <= 0) $errors[] = 'Selecciona un lote válido';
        if ($data['client_id'] <= 0) $errors[] = 'Selecciona un cliente válido';
        if ($data['total_price'] <= 0) $errors[] = 'Ingresa el precio total';
        if ($data['initial_payment'] < 0) $errors[] = 'La cuota inicial no puede ser negativa';
        if ($data['balance'] < 0) $errors[] = 'El saldo no puede ser negativo';
        if ($data['payment_term'] < 0) $errors[] = 'El plazo de pago debe ser positivo';

        // Validación extra: lote no puede estar ya vendido o cancelado (opcional pero recomendado)
       // $lot = $this->lotModel->getById($data['lot_id']);
       // if ($lot && in_array($lot['status'], ['vendido', 'cancelado'])) {
         //   $errors[] = 'Este lote ya está vendido o cancelado';
       // }

        if ($mode === 'create') {  // ← Solo validar esto al CREAR
        $lot = $this->lotModel->getById($data['lot_id']);
        if ($lot && in_array($lot['status'], ['vendido', 'cancelado'])) {
        $errors[] = 'Este lote ya está vendido o cancelado';
        }
        }

        if (!empty($errors)) {
            $this->setFlash('danger', implode('<br>', $errors));
            $this->render('lot-sales/form', [
                'title' => $title,
                'data' => $data,
                'mode' => $mode,
                'id' => $id,
                'lots' => $lots,
                'clients' => $clients
            ]);
            return;
        }

        if ($mode === 'create') {
            if ($this->saleModel->create($data)) {
                // Cambiar estado del lote automáticamente
                $this->lotModel->changeStatus($data['lot_id'], 'vendido');  // o 'reservado' si es reserva

                $this->setFlash('success', 'Venta/contrato registrado correctamente. Lote marcado como vendido.');
                $this->redirect('lot-sales');
            } else {
                $this->setFlash('danger', 'Error al registrar la venta.');
            }
        } else {
            if ($this->saleModel->update($id, $data)) {
                $this->setFlash('success', 'Venta actualizada correctamente.');
                $this->redirect('lot-sales');
            } else {
                $this->setFlash('danger', 'Error al actualizar la venta.');
            }
        }
    }

    $this->render('lot-sales/form', [
        'title' => $title,
        'data' => $data,
        'mode' => $mode,
        'id' => $id,
        'lots' => $lots,
        'clients' => $clients
    ]);
    }

    public function cancel(int $id)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->setFlash('danger', 'Acción no permitida.');
        $this->redirect('lot-sales');
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!$this->validateCsrfToken($token)) {
        $this->setFlash('danger', 'Error de seguridad.');
        $this->redirect('lot-sales');
    }

    $reason = trim($_POST['reason'] ?? '');

    if ($this->saleModel->cancel($id, $reason)) {
        $this->setFlash('success', 'Venta cancelada correctamente. El lote ha sido liberado.');
    } else {
        $this->setFlash('danger', 'Error al cancelar la venta.');
    }

    $this->redirect('lot-sales');
}
}
