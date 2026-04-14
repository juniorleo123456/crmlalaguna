<?php

// app/controllers/PartnersController.php

class PartnersController extends Controller
{
    private PartnersModel $partnerModel;

    public function __construct()
    {
        $this->requireLogin();

        // Solo admin puede gestionar socios
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar socios.');
            $this->redirect('dashboard');
        }

        $this->partnerModel = new PartnersModel(getDBConnection());
        $this->pdo          = getDBConnection(); // para consultas directas en métodos privados
    }

    /**
     * Listado de todos los socios
     */
    public function index()
    {
        $partners = $this->partnerModel->getAll();

        $this->render('partners/index', [
            'title'    => 'Listado de Socios / Partners',
            'partners' => $partners
        ]);
    }

    /**
     * Formulario para crear/editar socio
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
        $title = $mode === 'create' ? 'Nuevo Socio' : 'Editar Socio';

        if ($mode === 'edit') {
            $data = $this->partnerModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Socio no encontrado.');
                $this->redirect('partners');
            }
        }

        // Obtener usuarios con rol 'socio' que aún no están registrados como partners
        $availableUsers = $this->getAvailableUsersForPartner();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect("partners/{$mode}" . ($id ? "/$id" : ''));
            }

            $data = [
                'user_id'         => (int) ($_POST['user_id'] ?? 0),
                'commission_rate' => (float) ($_POST['commission_rate'] ?? 0),
                'commission_type' => $_POST['commission_type'] ?? 'percent',
                'notes'           => trim($_POST['notes'] ?? '')
            ];

            $errors = [];
            if ($data['user_id'] <= 0) {
                $errors[] = 'Selecciona un usuario válido';
            }
            if ($data['commission_rate'] < 0) {
                $errors[] = 'La comisión no puede ser negativa';
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('partners/form', [
                    'title'          => $title,
                    'data'           => $data,
                    'mode'           => $mode,
                    'id'             => $id,
                    'availableUsers' => $availableUsers
                ]);

                return;
            }

            if ($mode === 'create') {
                if ($this->partnerModel->create($data)) {
                    $this->setFlash('success', 'Socio registrado correctamente.');
                    $this->redirect('partners');
                } else {
                    $this->setFlash('danger', 'Error al registrar el socio.');
                }
            } else {
                if ($this->partnerModel->update($id, $data)) {
                    $this->setFlash('success', 'Socio actualizado correctamente.');
                    $this->redirect('partners');
                } else {
                    $this->setFlash('danger', 'Error al actualizar el socio.');
                }
            }
        }

        $this->render('partners/form', [
            'title'          => $title,
            'data'           => $data,
            'mode'           => $mode,
            'id'             => $id,
            'availableUsers' => $availableUsers
        ]);
    }

    /**
     * Obtiene usuarios con rol 'socio' que aún no están registrados como partners
     */
    private function getAvailableUsersForPartner(): array
    {
        // Usamos el PDO del modelo (mejor práctica)
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.name, u.email 
            FROM users u
            LEFT JOIN partners p ON u.id = p.user_id
            WHERE u.role = 'socio' 
              AND p.id IS NULL
            ORDER BY u.name ASC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cambia el estado del socio (activo/inactivo)
     */
    public function toggleStatus(int $id)
    {
        $partner = $this->partnerModel->getById($id);
        if (!$partner) {
            $this->setFlash('danger', 'Socio no encontrado.');
            $this->redirect('partners');
        }

        $newStatus = $partner['status'] === 'active' ? 'inactive' : 'active';

        if ($this->partnerModel->toggleStatus($id, $newStatus)) {
            $mensaje = $newStatus === 'active' ? 'activado' : 'desactivado';
            $this->setFlash('success', "Socio {$mensaje} correctamente.");
        } else {
            $this->setFlash('danger', 'Error al cambiar estado del socio.');
        }

        $this->redirect('partners');
    }
}
