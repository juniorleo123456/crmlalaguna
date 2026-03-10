<?php
// app/controllers/LotReservationsController.php

class LotReservationsController extends Controller
{
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

        $this->reservationModel = new LotReservationsModel(getDBConnection());
        $this->lotModel = new LotModel(getDBConnection());
        $this->clientModel = new ClientModel(getDBConnection());
    }

    public function index()
    {
        $reservations = $this->reservationModel->getAll();

        $this->render('lot-reservations/index', [
            'title' => 'Listado de Reservas',
            'reservations' => $reservations
        ]);
    }

    public function create()
    {
        $this->form('create');
    }

    private function form(string $mode, int $id = 0)
    {
        $data = [];
        $title = $mode === 'create' ? 'Nueva Reserva' : 'Editar Reserva';

        if ($mode === 'edit') {
            $data = $this->reservationModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Reserva no encontrada.');
                $this->redirect('lot-reservations');
            }
        }

        $lots = $this->lotModel->getAll(); // Filtrar solo 'disponible' más adelante
        $clients = $this->clientModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect("lot-reservations/{$mode}" . ($id ? "/$id" : ""));
            }

            $data = [
                'lot_id' => (int) ($_POST['lot_id'] ?? 0),
                'client_id' => (int) ($_POST['client_id'] ?? 0),
                'reservation_date' => trim($_POST['reservation_date'] ?? date('Y-m-d H:i:s')),
                'expiration_date' => trim($_POST['expiration_date'] ?? date('Y-m-d H:i:s', strtotime('+30 days'))),
                'amount' => (float) ($_POST['amount'] ?? 300.00),
                'notes' => trim($_POST['notes'] ?? '')
            ];

            $errors = [];
            if ($data['lot_id'] <= 0) $errors[] = 'Selecciona un lote válido';
            if ($data['client_id'] <= 0) $errors[] = 'Selecciona un cliente válido';
            if ($data['amount'] <= 0) $errors[] = 'Ingresa un monto válido';

            // Validación extra: lote debe estar disponible (solo en create)
            if ($mode === 'create') {
                $lot = $this->lotModel->getById($data['lot_id']);
                if ($lot && $lot['status'] !== 'disponible') {
                    $errors[] = 'Este lote no está disponible para reservar';
                }
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('lot-reservations/form', [
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
                if ($this->reservationModel->create($data)) {
                    // Cambiar estado del lote a 'reservado'
                    $this->lotModel->changeStatus($data['lot_id'], 'reservado');

                    $this->setFlash('success', 'Reserva registrada correctamente. Lote marcado como reservado.');
                    $this->redirect('lot-reservations');
                } else {
                    $this->setFlash('danger', 'Error al registrar la reserva.');
                }
            }
            // Edit y cancelar los agregamos después
        }

        $this->render('lot-reservations/form', [
            'title' => $title,
            'data' => $data,
            'mode' => $mode,
            'id' => $id,
            'lots' => $lots,
            'clients' => $clients
        ]);
    }
}
