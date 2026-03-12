<?php
// app/controllers/BlocksController.php

class BlocksController extends Controller
{
    private BlockModel $blockModel;

    public function __construct()
    {
        $this->requireLogin();

        // Solo admin puede gestionar manzanas/bloques
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar manzanas.');
            $this->redirect('dashboard');
        }

        $this->blockModel = new BlockModel(getDBConnection());
    }

    public function index()
    {
        $blocks = $this->blockModel->getAll();

        // Calcular conteo real de lotes para cada manzana
         foreach ($blocks as &$block) {
        $block['real_lots'] = $this->blockModel->countRealLots($block['id']);
         }
         unset($block); // buena práctica para evitar referencia accidental

        $this->render('manzanas/index', [
            'title'  => 'Listado de Manzanas',
            'blocks' => $blocks
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
        $title = $mode === 'create' ? 'Nueva Manzana' : 'Editar Manzana';

        if ($mode === 'edit') {
            $data = $this->blockModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Manzana no encontrada.');
                $this->redirect('blocks');
            }
        }

        // Cargar lista de proyectos activos para el select
        $projectModel = new ProjectModel(getDBConnection());
        $projectsList = $projectModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de validación de seguridad.');
                $this->redirect("blocks/{$mode}" . ($id ? "/$id" : ""));
            }

            // Recoger datos del formulario
            $data = [
                'project_id'           => (int) ($_POST['project_id'] ?? 0),
                'name'                 => trim($_POST['name'] ?? ''),
                'description'          => trim($_POST['description'] ?? ''),
                'total_lots'           => (int) ($_POST['total_lots'] ?? 0),
                'min_monthly_payment'  => (float) ($_POST['min_monthly_payment'] ?? 0),
                'initial_payment'      => (float) ($_POST['initial_payment'] ?? 0)
            ];

            // Validaciones básicas (lado servidor)
            $errors = [];
            if ($data['project_id'] <= 0 || !$projectModel->projectExists($data['project_id'])) $errors[] = 'Debes seleccionar un proyecto válido';
            if (empty($data['name'])) $errors[] = 'El nombre de la manzana es obligatorio';
            if ($data['total_lots'] <= 0) $errors[] = 'El total de lotes debe ser mayor a 0';
            if ($data['min_monthly_payment'] <= 0) $errors[] = 'El pago mensual mínimo debe ser mayor a 0';
            if ($data['initial_payment'] < 0) $errors[] = 'La cuota inicial no puede ser negativa';

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('manzanas/form', [
                    'title' => $title,
                    'data'  => $data,
                    'mode'  => $mode,
                    'id'    => $id
                ]);
                return;
            }

            if ($mode === 'create') {
                if ($this->blockModel->create($data)) {
                    $this->setFlash('success', 'Manzana creada correctamente.');
                    $this->redirect('blocks');
                } else {
                    $this->setFlash('danger', 'Error al crear la manzana.');
                }
            } else {
                if ($this->blockModel->update($id, $data)) {
                    $this->setFlash('success', 'Manzana actualizada correctamente.');
                    $this->redirect('blocks');
                } else {
                    $this->setFlash('danger', 'Error al actualizar la manzana.');
                }
            }
        }

        // GET: mostrar formulario
        $this->render('manzanas/form', [
            'title' => $title,
            'data'  => $data,
            'mode'  => $mode,
            'id'    => $id,
            'projectsList' => $projectsList
        ]);
    }

    public function toggleStatus(int $id)
    {
        $block = $this->blockModel->getById($id);

        if (!$block) {
            $this->setFlash('danger', 'Manzana no encontrada.');
            $this->redirect('blocks');
        }

        $newStatus = $block['status'] === 'active' ? 'inactive' : 'active';

        if ($this->blockModel->toggleStatus($id, $newStatus)) {
            $this->setFlash('success', "Manzana <strong>" . htmlspecialchars($block['name']) . "</strong> " . ($newStatus === 'active' ? 'activada' : 'desactivada') . " correctamente.");
        } else {
            $this->setFlash('danger', "Error al cambiar estado de la manzana <strong>" . htmlspecialchars($block['name']) . "</strong>.");
        }

        $this->redirect('blocks');
    }
}
 