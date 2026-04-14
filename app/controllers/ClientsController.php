<?php

// app/controllers/ClientsController.php

class ClientsController extends Controller
{
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->requireLogin();

        // Solo admin puede gestionar clientes
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar clientes.');
            $this->redirect('dashboard');
        }

        $this->clientModel = new ClientModel(getDBConnection());
    }

    public function index()
    {
        $clients = $this->clientModel->getAll();

        $this->render('clients/index', [
            'title'   => 'Listado de Clientes',
            'clients' => $clients
        ]);
    }

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
        $title = $mode === 'create' ? 'Nuevo Cliente' : 'Editar Cliente';

        if ($mode === 'edit') {
            $data = $this->clientModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Cliente no encontrado.');
                $this->redirect('clients');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de validación de seguridad.');
                $this->redirect("clients/{$mode}" . ($id ? "/$id" : ''));
            }

            // Recoger datos
            $data = [
                'name'         => trim($_POST['name'] ?? ''),
                'email'        => trim($_POST['email'] ?? ''),
                'password'     => trim($_POST['password'] ?? ''),
                'phone'        => trim($_POST['phone'] ?? ''),
                'address'      => trim($_POST['address'] ?? ''),
                'city'         => trim($_POST['city'] ?? ''),
                'state'        => trim($_POST['state'] ?? ''),
                'postal_code'  => trim($_POST['postal_code'] ?? ''),
                'company_name' => trim($_POST['company_name'] ?? ''),
                'tax_id'       => trim($_POST['tax_id'] ?? ''),
                'notes'        => trim($_POST['notes'] ?? ''),
                'phone'        => trim($_POST['phone'] ?? '')
            ];

            // Validaciones básicas
            $errors = [];
            if (empty($data['name'])) {
                $errors[] = 'El nombre es obligatorio';
            }
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            }
            if ($mode === 'create' && (empty($data['password']) || strlen($data['password']) < 6)) {
                $errors[] = 'Contraseña mínima 6 caracteres';
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('clients/form', [
                    'title' => $title,
                    'data'  => $data,
                    'mode'  => $mode,
                    'id'    => $id
                ]);

                return;
            }

            if ($mode === 'create') {
                // Crear usuario primero
                $userStmt = getDBConnection()->prepare("
                    INSERT INTO users (name, email, password_hash, phone, role, status)
                    VALUES (?, ?, ?, ?, 'cliente', 'active')
                ");
                $userStmt->execute([
                    $data['name'],
                    $data['email'],
                    password_hash($data['password'], PASSWORD_DEFAULT),
                    $data['phone']
                ]);
                $userId = getDBConnection()->lastInsertId();

                // Crear cliente
                $clientStmt = getDBConnection()->prepare('
                    INSERT INTO clients (user_id, address, city, state, postal_code, company_name, tax_id, notes, phone)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ');
                $clientStmt->execute([
                    $userId,
                    $data['address'],
                    $data['city'],
                    $data['state'],
                    $data['postal_code'],
                    $data['company_name'],
                    $data['tax_id'],
                    $data['notes'],
                    $data['phone']
                ]);

                $this->setFlash('success', 'Cliente creado correctamente.');
                $this->redirect('clients');
            } else {
                // Editar cliente (no cambiamos password ni user)
                if ($this->clientModel->update($id, $data)) {
                    $this->setFlash('success', 'Cliente actualizado correctamente.');
                    $this->redirect('clients');
                } else {
                    $this->setFlash('danger', 'Error al actualizar cliente.');
                }
            }
        }

        // GET: mostrar formulario
        $this->render('clients/form', [
            'title' => $title,
            'data'  => $data,
            'mode'  => $mode,
            'id'    => $id
        ]);
    }

    public function toggleStatus(int $id)
    {
        $client = $this->clientModel->getById($id);

        if (!$client) {
            $this->setFlash('danger', 'Cliente no encontrado.');
            $this->redirect('clients');
        }

        $newStatus = $client['status'] === 'active' ? 'inactive' : 'active';

        if ($this->clientModel->toggleStatus($id, $newStatus)) {
            $message = 'Cliente <strong>' . htmlspecialchars($client['name']) . '</strong> ' . ($newStatus === 'active' ? 'activado' : 'desactivado') . ' correctamente.';

            // Si desactivamos, cerrar TODAS las sesiones del usuario asociado
            if ($newStatus === 'inactive') {
                $sessionManager = new SessionManager(getDBConnection());
                $sessionManager->destroyUserSessions($client['user_id']);
                $message .= ' Todas las sesiones del cliente han sido cerradas.';
            }

            $this->setFlash('success', $message);
        } else {
            $this->setFlash('danger', 'Error al cambiar estado del cliente <strong>' . htmlspecialchars($client['name']) . '</strong>.');
        }

        $this->redirect('clients');
    }
    public function view(int $id)
    {
        $client = $this->clientModel->getById($id);
        if (!$client) {
            $this->setFlash('danger', 'Cliente no encontrado.');
            $this->redirect('clients');
        }

        // Cargar servicios/proyectos asociados
        $stmt = getDBConnection()->prepare('
        SELECT cs.*, p.title AS project_title, s.name AS service_name
        FROM client_services cs
        LEFT JOIN projects p ON cs.project_id = p.id
        LEFT JOIN services s ON cs.service_id = s.id
        WHERE cs.client_id = ?
        ORDER BY cs.created_at DESC
    ');
        $stmt->execute([$id]);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('clients/view', [
            'title'    => 'Ficha del Cliente: ' . $client['name'],
            'client'   => $client,
            'services' => $services
        ]);
    }
}
