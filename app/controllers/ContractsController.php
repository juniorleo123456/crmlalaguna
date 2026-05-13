<?php
// app/controllers/ContractsController.php

class ContractsController extends Controller
{
    private ContractsModel $contractModel;
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar contratos.');
            $this->redirect('dashboard');
        }

        $this->contractModel = new ContractsModel(getDBConnection());
        $this->clientModel   = new ClientModel(getDBConnection());
    }

    /**
     * Listado de contratos (por cliente o todos)
     */
    public function index()
    {
        $clientId = (int) ($_GET['client_id'] ?? 0);
        $contracts = $this->contractModel->getAll($clientId);

        $this->render('contracts/index', [
            'title'     => 'Listado de Contratos',
            'contracts' => $contracts,
            'clientId'  => $clientId
        ]);
    }

    /**
     * Formulario para subir nuevo contrato
     */
    public function create()
    {
        $this->form('create');
    }

    private function form(string $mode)
    {
        $data = [];
        $title = 'Subir Nuevo Contrato';

        // Obtener clientes para el select
        $clients = $this->clientModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect('contracts/create');
            }

            // Validación básica
            if (empty($_POST['client_id']) || empty($_POST['contract_type'])) {
                $this->setFlash('danger', 'Debe seleccionar cliente y tipo de contrato.');
                $this->render('contracts/form', [
                    'title'   => $title,
                    'data'    => $_POST,
                    'clients' => $clients
                ]);
                return;
            }

            // Manejo de archivo
            if (!isset($_FILES['contract_file']) || $_FILES['contract_file']['error'] !== UPLOAD_ERR_OK) {
                $this->setFlash('danger', 'Debe subir un archivo válido.');
                $this->render('contracts/form', [
                    'title'   => $title,
                    'data'    => $_POST,
                    'clients' => $clients
                ]);
                return;
            }

            $file = $_FILES['contract_file'];
            $uploadDir = __DIR__ . '/../../public/uploads/contracts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . basename($file['name']);
            $filePath = 'uploads/contracts/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                $data = [
                    'client_id'     => (int)$_POST['client_id'],
                    'lot_sale_id'   => !empty($_POST['lot_sale_id']) ? (int)$_POST['lot_sale_id'] : null,
                    'contract_type' => trim($_POST['contract_type']),
                    'file_path'     => $filePath,
                    'file_name'     => $file['name'],
                    'description'   => trim($_POST['description'] ?? ''),
                    'signed_date'   => !empty($_POST['signed_date']) ? $_POST['signed_date'] : null,
                    'status'        => 'uploaded'
                ];

                if ($this->contractModel->create($data)) {
                    $this->setFlash('success', 'Contrato subido correctamente.');
                    $this->redirect('contracts');
                } else {
                    $this->setFlash('danger', 'Error al guardar el contrato en la base de datos.');
                }
            } else {
                $this->setFlash('danger', 'Error al subir el archivo.');
            }
        }

        $this->render('contracts/form', [
            'title'   => $title,
            'data'    => $data,
            'clients' => $clients
        ]);
    }
}