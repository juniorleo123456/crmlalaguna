<?php

// app/controllers/SociosController.php

class SociosController extends Controller
{
    private SociosModel $socioModel;

    public function __construct()
    {
        $this->requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar socios.');
            $this->redirect('dashboard');
        }

        $this->socioModel = new SociosModel(getDBConnection());
    }

    /**
     * Listado de todos los socios
     */
    public function index()
    {
        $socios = $this->socioModel->getAll();

        $this->render('socios/index', [
            'title'  => 'Listado de Socios',
            'socios' => $socios
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
            $data = $this->socioModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Socio no encontrado.');
                $this->redirect('socios');
            }
        }

        // Obtener usuarios con rol 'socio' que aún no tienen registro en socios
        $availableUsers = $this->getAvailableUsersForSocio();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect("socios/{$mode}" . ($id ? "/$id" : ''));
            }

            $data = [
                'user_id'         => (int) ($_POST['user_id'] ?? 0),
                'partner_id'      => (int) ($_POST['partner_id'] ?? null),
                'nombre_empresa'  => trim($_POST['nombre_empresa'] ?? ''),
                'direccion'       => trim($_POST['direccion'] ?? ''),
                'ciudad'          => trim($_POST['ciudad'] ?? ''),
                'telefono_extra'  => trim($_POST['telefono_extra'] ?? ''),
                'banco'           => trim($_POST['banco'] ?? ''),
                'cuenta_bancaria' => trim($_POST['cuenta_bancaria'] ?? ''),
                'tipo_cuenta'     => trim($_POST['tipo_cuenta'] ?? ''),
                'notes'           => trim($_POST['notes'] ?? '')
            ];

            $errors = [];
            if ($data['user_id'] <= 0) {
                $errors[] = 'Selecciona un usuario válido';
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('socios/form', [
                    'title'          => $title,
                    'data'           => $data,
                    'mode'           => $mode,
                    'id'             => $id,
                    'availableUsers' => $availableUsers
                ]);

                return;
            }

            if ($mode === 'create') {
                if ($this->socioModel->create($data)) {
                    $this->setFlash('success', 'Socio registrado correctamente.');
                    $this->redirect('socios');
                } else {
                    $this->setFlash('danger', 'Error al registrar el socio.');
                }
            } else {
                if ($this->socioModel->update($id, $data)) {
                    $this->setFlash('success', 'Socio actualizado correctamente.');
                    $this->redirect('socios');
                } else {
                    $this->setFlash('danger', 'Error al actualizar el socio.');
                }
            }
        }

        $this->render('socios/form', [
            'title'          => $title,
            'data'           => $data,
            'mode'           => $mode,
            'id'             => $id,
            'availableUsers' => $availableUsers
        ]);
    }

    /**
     * Obtiene usuarios con rol 'socio' que aún no tienen registro en la tabla socios
     */
    private function getAvailableUsersForSocio(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.name, u.email 
            FROM users u
            LEFT JOIN socios s ON u.id = s.user_id
            WHERE u.role = 'socio' 
              AND s.id IS NULL
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
        $socio = $this->socioModel->getById($id);
        if (!$socio) {
            $this->setFlash('danger', 'Socio no encontrado.');
            $this->redirect('socios');
        }

        $newStatus = $socio['status'] === 'active' ? 'inactive' : 'active';

        if ($this->socioModel->toggleStatus($id, $newStatus)) {
            $mensaje = $newStatus === 'active' ? 'activado' : 'desactivado';
            $this->setFlash('success', "Socio {$mensaje} correctamente.");
        } else {
            $this->setFlash('danger', 'Error al cambiar estado del socio.');
        }

        $this->redirect('socios');
    }
}
