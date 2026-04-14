<?php

// app/controllers/LotPaymentsController.php

class LotPaymentsController extends Controller
{
    private LotPaymentsModel $paymentModel;
    private LotSalesModel $saleModel;
    private LotReservationsModel $reservationModel;
    private LotModel $lotModel;
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido.');
            $this->redirect('dashboard');
        }

        $this->paymentModel     = new LotPaymentsModel(getDBConnection());
        $this->saleModel        = new LotSalesModel(getDBConnection());
        $this->reservationModel = new LotReservationsModel(getDBConnection());
        $this->lotModel         = new LotModel(getDBConnection());
        $this->clientModel      = new ClientModel(getDBConnection());
    }

    /**
     * Listado completo de todos los pagos
     */
    public function index()
    {
        $payments = $this->paymentModel->getAll();

        $this->render('lot-payments/index', [
            'title'    => 'Listado de Pagos',
            'payments' => $payments
        ]);
    }

    /**
     * Listado solo de pagos con boleta/comprobante subido
     */
    public function receipts()
    {
        $receipts = $this->paymentModel->getPaymentsWithReceipt();

        $this->render('lot-reciepts/index', [
            'title'    => 'Boletas y Comprobantes',
            'receipts' => $receipts
        ]);
    }

    /**
     * Formulario para registrar un nuevo pago
     */
    public function create()
    {
        $this->form('create');
    }

    private function form(string $mode, int $id = 0)
    {
        $data  = [];
        $title = $mode === 'create' ? 'Registrar Nuevo Pago' : 'Editar Pago';

        if ($mode === 'edit') {
            $data = $this->paymentModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Pago no encontrado.');
                $this->redirect('lot-payments');
            }
        }

        $sales        = $this->saleModel->getAll();
        $reservations = $this->reservationModel->getAll();
        $paymentTypes = ['reserva', 'inicial', 'cuota_fija', 'cuota_minima', 'adelanto', 'saldo_final', 'mora'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect("lot-payments/{$mode}" . ($id ? "/$id" : ''));
            }

            $data = [
                'lot_sale_id'        => (int) ($_POST['lot_sale_id'] ?? null),
                'lot_reservation_id' => (int) ($_POST['lot_reservation_id'] ?? null),
                'payment_date'       => trim($_POST['payment_date'] ?? date('Y-m-d')),
                'amount'             => (float) ($_POST['amount'] ?? 0),
                'payment_type'       => trim($_POST['payment_type'] ?? ''),
                'payment_method'     => trim($_POST['payment_method'] ?? 'efectivo'),
                'receipt_number'     => trim($_POST['receipt_number'] ?? null),
                'receipt_file'       => $data['receipt_file'] ?? null, // se maneja con upload
                'is_late'            => isset($_POST['is_late']) ? 1 : 0,
                'late_fee'           => (float) ($_POST['late_fee'] ?? 0),
                'notes'              => trim($_POST['notes'] ?? '')
            ];

            $errors = [];
            if ($data['amount'] <= 0) {
                $errors[] = 'El monto debe ser mayor a 0';
            }
            if (empty($data['payment_type'])) {
                $errors[] = 'Selecciona un tipo de pago';
            }
            if (!$data['lot_sale_id'] && !$data['lot_reservation_id']) {
                $errors[] = 'Asocia el pago a una venta o reserva';
            }

            // Manejo de archivo (upload)
            if (!empty($_FILES['receipt_file']['name'])) {
                $uploadDir = 'uploads/boletas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName   = time() . '-' . basename($_FILES['receipt_file']['name']);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $targetFile)) {
                    $data['receipt_file'] = $targetFile;
                } else {
                    $errors[] = 'Error al subir el archivo de boleta';
                }
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('lot-payments/form', [
                    'title'        => $title,
                    'data'         => $data,
                    'mode'         => $mode,
                    'id'           => $id,
                    'sales'        => $sales,
                    'reservations' => $reservations,
                    'paymentTypes' => $paymentTypes
                ]);

                return;
            }

            if ($mode === 'create') {

                // Validar que el monto no exceda el saldo pendiente (si está asociado a venta)
                if (!empty($data['lot_sale_id']) && $data['lot_sale_id'] > 0) {
                    $sale = $this->saleModel->getById($data['lot_sale_id']);
                    if ($sale && $sale['balance'] > 0 && $data['amount'] > $sale['balance']) {
                        $errors[] = 'El monto del pago (S/ ' . number_format($data['amount'], 2) . ') no puede ser mayor al saldo pendiente (S/ ' . number_format($sale['balance'], 2) . ').';
                    }
                }
                if ($this->paymentModel->create($data)) {
                    $this->setFlash('success', 'Pago registrado correctamente.');

                    // Redirección inteligente según asociación
                    if (!empty($data['lot_sale_id']) && $data['lot_sale_id'] > 0) {
                        $this->redirect('lot-sales');  // vuelve al listado de ventas → ve el saldo actualizado
                    } elseif (!empty($data['lot_reservation_id']) && $data['lot_reservation_id'] > 0) {
                        $this->redirect('lot-reservations');  // opcional: si asocias a reserva, vuelve ahí
                    } else {
                        $this->redirect('lot-payments');  // sin asociación → vuelve a pagos
                    }
                } else {
                    $this->setFlash('danger', 'Error al registrar el pago.');
                }
            }
            // Edit lo agregamos en la siguiente iteración
        }

        $this->render('lot-payments/form', [
            'title'        => $title,
            'data'         => $data,
            'mode'         => $mode,
            'id'           => $id,
            'sales'        => $sales,
            'reservations' => $reservations,
            'paymentTypes' => $paymentTypes
        ]);
    }
}
