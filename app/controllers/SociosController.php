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

    public function index()
    {
        $socios = $this->socioModel->getAll();

        $this->render('socios/index', [
            'title'  => 'Listado de Socios',
            'socios' => $socios
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
        $title = $mode === 'create' ? 'Nuevo Socio' : 'Editar Socio';

        if ($mode === 'edit') {
            $data = $this->socioModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Socio no encontrado.');
                $this->redirect('socios');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de seguridad.');
                $this->redirect("socios/{$mode}" . ($id ? "/$id" : ""));
            }

            $data = [
                'name'            => trim($_POST['name'] ?? ''),
                'email'           => trim($_POST['email'] ?? ''),
                'password'        => trim($_POST['password'] ?? ''),
                'phone'           => trim($_POST['phone'] ?? ''),
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
            if (empty($data['name'])) $errors[] = 'El nombre es obligatorio';
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            }
            if ($mode === 'create' && (empty($data['password']) || strlen($data['password']) < 6)) {
                $errors[] = 'La contraseña debe tener mínimo 6 caracteres';
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('socios/form', [
                    'title' => $title,
                    'data'  => $data,
                    'mode'  => $mode,
                    'id'    => $id
                ]);
                return;
            }

            if ($mode === 'create') {
                // 1. Crear usuario con rol 'socio'
                $userStmt = getDBConnection()->prepare("
                    INSERT INTO users (name, email, password_hash, phone, role, status, created_at)
                    VALUES (?, ?, ?, ?, 'socio', 'active', NOW())
                ");
                $userStmt->execute([
                    $data['name'],
                    $data['email'],
                    password_hash($data['password'], PASSWORD_DEFAULT),
                    $data['phone']
                ]);
                $userId = getDBConnection()->lastInsertId();

                // 2. Crear registro en tabla socios
                $socioStmt = getDBConnection()->prepare("
                    INSERT INTO socios 
                    (user_id, nombre_empresa, direccion, ciudad, telefono_extra, 
                     banco, cuenta_bancaria, tipo_cuenta, notes, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())
                ");
                $socioStmt->execute([
                    $userId,
                    $data['nombre_empresa'],
                    $data['direccion'],
                    $data['ciudad'],
                    $data['telefono_extra'],
                    $data['banco'],
                    $data['cuenta_bancaria'],
                    $data['tipo_cuenta'],
                    $data['notes']
                ]);

                $this->setFlash('success', 'Socio creado correctamente.');
                $this->redirect('socios');
            } else {
                // Editar solo datos de socio (no cambiamos usuario ni contraseña)
                if ($this->socioModel->update($id, $data)) {
                    $this->setFlash('success', 'Socio actualizado correctamente.');
                    $this->redirect('socios');
                } else {
                    $this->setFlash('danger', 'Error al actualizar socio.');
                }
            }
        }

        // GET: mostrar formulario
        $this->render('socios/form', [
            'title' => $title,
            'data'  => $data,
            'mode'  => $mode,
            'id'    => $id
        ]);
    }

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
            $this->setFlash('success', "Socio <strong>" . htmlspecialchars($socio['name']) . "</strong> {$mensaje} correctamente.");
        } else {
            $this->setFlash('danger', 'Error al cambiar estado del socio.');
        }

        $this->redirect('socios');
    }
}