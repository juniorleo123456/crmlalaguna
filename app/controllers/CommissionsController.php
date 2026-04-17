<?php
// app/controllers/CommissionsController.php

class CommissionsController extends Controller
{
    private CommissionsModel $commissionModel;
    private SociosModel $socioModel;

    public function __construct()
    {
        $this->requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido.');
            $this->redirect('dashboard');
        }

        $this->commissionModel = new CommissionsModel(getDBConnection());
        $this->socioModel      = new SociosModel(getDBConnection());
    }

    public function index()
    {
        $commissions = $this->commissionModel->getAll();

        $this->render('commissions/index', [
            'title'       => 'Listado de Comisiones',
            'commissions' => $commissions
        ]);
    }

    public function create()
    {
        $this->form('create');
    }

    private function form(string $mode)
    {
        $data = [];
        $title = 'Registrar Nueva Comisión';

        $socios = $this->socioModel->getAll();   // para el select

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect('partners/comisiones/create');
            }

            $data = [
                'socio_id'     => (int)($_POST['socio_id'] ?? 0),
                'lot_sale_id'  => !empty($_POST['lot_sale_id']) ? (int)$_POST['lot_sale_id'] : null,
                'amount'       => (float)($_POST['amount'] ?? 0),
                'payment_date' => $_POST['payment_date'] ?? date('Y-m-d'),
                'notes'        => trim($_POST['notes'] ?? '')
            ];

            if ($data['socio_id'] <= 0 || $data['amount'] <= 0) {
                $this->setFlash('danger', 'Debe seleccionar un socio y un monto válido.');
                $this->render('commissions/form', [
                    'title'  => $title,
                    'data'   => $data,
                    'socios' => $socios
                ]);
                return;
            }

            if ($this->commissionModel->create($data)) {
                $this->setFlash('success', 'Comisión registrada correctamente.');
                $this->redirect('partners/comisiones');
            } else {
                $this->setFlash('danger', 'Error al registrar la comisión.');
            }
        }

        $this->render('commissions/form', [
            'title'  => $title,
            'data'   => $data,
            'socios' => $socios
        ]);
    }
}