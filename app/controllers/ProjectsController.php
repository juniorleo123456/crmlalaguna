<?php
// app/controllers/ProjectsController.php

class ProjectsController extends Controller
{
    private ProjectModel $projectModel;

    public function __construct()
    {
        $this->requireLogin();

        // Solo admin puede gestionar proyectos (crear/editar/cambiar status)
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar proyectos.');
            $this->redirect('dashboard');
        }

        $this->projectModel = new ProjectModel(getDBConnection());
    }

    public function index()
{
    $page = (int) ($_GET['page'] ?? 1);
    $perPage = 10; // puedes mover a constante o config más adelante
    $search = trim($_GET['search'] ?? '');
    $status = trim($_GET['status'] ?? '');
    $clientId = (int) ($_GET['client_id'] ?? 0);

    $projects = $this->projectModel->getAllFiltered($page, $perPage, $search, $status, $clientId);
    $total = $this->projectModel->countFiltered($search, $status, $clientId);
    $totalPages = max(1, ceil($total / $perPage));
    $statuses = $this->projectModel->getStatuses();

    $this->render('projects/index', [
        'title'       => 'Listado de Proyectos',
        'projects'    => $projects,
        'page'        => $page,
        'totalPages'  => $totalPages,
        'search'      => $search,
        'status'      => $status,
        'client_id'   => $clientId,
        'statuses'    => $statuses
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
        $data = [];
        $title = $mode === 'create' ? 'Nuevo Proyecto' : 'Editar Proyecto';

        if ($mode === 'edit') {
            $data = $this->projectModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Proyecto no encontrado.');
                $this->redirect('projects');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de validación de seguridad.');
                $this->redirect("projects/{$mode}" . ($id ? "/$id" : ""));
            }

            // Recoger datos
            $data = [
                'title'       => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'status'      => trim($_POST['status'] ?? 'planificacion'),
                'start_date'  => trim($_POST['start_date'] ?? ''),
                'end_date'    => trim($_POST['end_date'] ?? ''),
                'progress'    => (int) ($_POST['progress'] ?? 0)
            ];

            // Validaciones básicas
            $errors = [];
            if (empty($data['title'])) $errors[] = 'El título del proyecto es obligatorio';
            if ($data['progress'] < 0 || $data['progress'] > 100) $errors[] = 'Progreso debe estar entre 0 y 100';

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('projects/form', [
                    'title' => $title,
                    'data'  => $data,
                    'mode'  => $mode,
                    'id'    => $id
                ]);
                return;
            }

            if ($mode === 'create') {
                if ($this->projectModel->create($data)) {
                    $this->setFlash('success', 'Proyecto creado correctamente.');
                    $this->redirect('projects');
                } else {
                    $this->setFlash('danger', 'Error al crear el proyecto.');
                }
            } else {
                if ($this->projectModel->update($id, $data)) {
                    $this->setFlash('success', 'Proyecto actualizado correctamente.');
                    $this->redirect('projects');
                } else {
                    $this->setFlash('danger', 'Error al actualizar el proyecto.');
                }
            }
        }

        // GET: mostrar formulario
        $this->render('projects/form', [
            'title' => $title,
            'data'  => $data,
            'mode'  => $mode,
            'id'    => $id
        ]);
    }

    public function changeStatus(int $id)
    {
        $project = $this->projectModel->getById($id);

        if (!$project) {
            $this->setFlash('danger', 'Proyecto no encontrado.');
            $this->redirect('projects');
        }

        // Ciclo simple entre estados (puedes cambiar a un select en el formulario si prefieres)
        $statuses = ['planificacion', 'ejecucion', 'entregado', 'cancelado'];
        $currentIndex = array_search($project['status'], $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $newStatus = $statuses[$nextIndex];

        if ($this->projectModel->changeStatus($id, $newStatus)) {
            $this->setFlash('success', "Estado del proyecto actualizado a <strong>" . ucfirst($newStatus) . "</strong>.");
        } else {
            $this->setFlash('danger', 'Error al cambiar el estado del proyecto.');
        }

        $this->redirect('projects');
    }
}
