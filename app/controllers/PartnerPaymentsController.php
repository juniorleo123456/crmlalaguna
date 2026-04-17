<?php
// app/controllers/PartnerPaymentsController.php

class PartnerPaymentsController extends Controller
{
    private PartnerPaymentsModel $paymentModel;
    private SociosModel $socioModel;

    public function __construct()
    {
        $this->requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar comisiones.');
            $this->redirect('dashboard');
        }

        $this->paymentModel = new PartnerPaymentsModel(getDBConnection());
        $this->socioModel   = new SociosModel(getDBConnection());
    }

    /**
     * Listado de todos los pagos mensuales a socios
     */
    public function index()
    {
        $payments = $this->paymentModel->getAll();

        $this->render('partner_payments/index', [
            'title'   => 'Comisiones Mensuales - Socios',
            'payments'=> $payments
        ]);
    }

    /**
     * Formulario para registrar nuevo pago mensual
     */
    public function create()
    {
        $this->form('create');
    }

        public function edit(int $id)
    {
        $this->form('edit', $id);
    }

    private function form(string $mode, int $id = 0)
    {
        $data  = [];
        $title = $mode === 'create' ? 'Registrar Pago Mensual' : 'Editar Pago Mensual';

        if ($mode === 'edit') {
            $data = $this->paymentModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Pago no encontrado.');
                $this->redirect('partners/comisiones');
            }
        }

        $socios = $this->socioModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect("partners/comisiones/{$mode}" . ($id ? "/$id" : ""));
            }

            $data = [
                'socio_id'           => (int)($_POST['socio_id'] ?? 0),
                'periodo'            => $_POST['periodo'] ?? date('Y-m-01'),
                'total_ingresos_mes' => (float)($_POST['total_ingresos_mes'] ?? 0),
                'monto_pago'         => (float)($_POST['monto_pago'] ?? 0),
                'tipo_comision'      => $_POST['tipo_comision'] ?? 'percent',
                'porcentaje'         => !empty($_POST['porcentaje']) ? (float)$_POST['porcentaje'] : null,
                'monto_fijo'         => !empty($_POST['monto_fijo']) ? (float)$_POST['monto_fijo'] : null,
                'notes'              => trim($_POST['notes'] ?? '')
            ];

            $errors = [];
            if ($data['socio_id'] <= 0) $errors[] = 'Debe seleccionar un socio';
            if ($data['monto_pago'] <= 0) $errors[] = 'El monto a pagar debe ser mayor a 0';

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('partner_payments/form', [
                    'title'  => $title,
                    'data'   => $data,
                    'socios' => $socios,
                    'mode'   => $mode,
                    'id'     => $id
                ]);
                return;
            }

            if ($mode === 'create') {
                if ($this->paymentModel->create($data)) {
                    $this->setFlash('success', 'Pago mensual registrado correctamente.');
                    $this->redirect('partners/comisiones');
                }
            } else {
                if ($this->paymentModel->update($id, $data)) {
                    $this->setFlash('success', 'Pago mensual actualizado correctamente.');
                    $this->redirect('partners/comisiones');
                }
            }
        }

        $this->render('partner_payments/form', [
            'title'  => $title,
            'data'   => $data,
            'socios' => $socios,
            'mode'   => $mode,
            'id'     => $id
        ]);
    }
}