<?php

// app/controllers/LotsController.php

class LotsController extends Controller
{
    private LotModel $lotModel;
    private BlockModel $blockModel;

    public function __construct()
    {
        $this->requireLogin();

        // Solo admin puede gestionar lotes
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden gestionar lotes.');
            $this->redirect('dashboard');
        }

        $this->lotModel   = new LotModel(getDBConnection());
        $this->blockModel = new BlockModel(getDBConnection());
    }

    public function index()
    {
        $page    = (int) ($_GET['page'] ?? 1);
        $perPage = 10;
        $blockId = (int) ($_GET['block_id'] ?? 0);
        $status  = trim($_GET['status'] ?? '');

        $lots       = $this->lotModel->getAllFiltered($page, $perPage, $blockId, $status);
        $total      = $this->lotModel->countFiltered($blockId, $status);
        $totalPages = max(1, ceil($total / $perPage));

        // Cargar manzanas para el filtro
        $blocks = $this->blockModel->getAll();

        // Estados para el filtro
        $statuses = ['disponible', 'reservado', 'vendido', 'mora', 'cancelado'];

        $this->render('lots/index', [
            'title'      => 'Listado de Lotes',
            'lots'       => $lots,
            'page'       => $page,
            'totalPages' => $totalPages,
            'block_id'   => $blockId,
            'status'     => $status,
            'blocks'     => $blocks,
            'statuses'   => $statuses
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
        $title = $mode === 'create' ? 'Nuevo Lote' : 'Editar Lote';

        if ($mode === 'edit') {
            $data = $this->lotModel->getById($id);
            if (!$data) {
                $this->setFlash('danger', 'Lote no encontrado.');
                $this->redirect('lots');
            }
        }

        // Cargar manzanas para el select
        $blocks = $this->blockModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de validación de seguridad.');
                $this->redirect("lots/{$mode}" . ($id ? "/$id" : ''));
            }

            $data = [
                'block_id'          => (int) ($_POST['block_id'] ?? 0),
                'lot_number'        => trim($_POST['lot_number'] ?? ''),
                'area'              => (float) ($_POST['area'] ?? 0),
                'front'             => (float) ($_POST['front'] ?? null),
                'depth'             => (float) ($_POST['depth'] ?? null),
                'price'             => (float) ($_POST['price'] ?? 0),
                'status'            => trim($_POST['status'] ?? 'disponible'),
                'is_corner'         => isset($_POST['is_corner']) ? 1 : 0,
                'faces_park'        => isset($_POST['faces_park']) ? 1 : 0,
                'faces_main_street' => isset($_POST['faces_main_street']) ? 1 : 0,
                'jiron_principal'   => isset($_POST['jiron_principal']) ? 1 : 0,
                'calle_1'           => isset($_POST['calle_1']) ? 1 : 0,
                'calle_2'           => isset($_POST['calle_2']) ? 1 : 0,
                'pasaje_1_parque'   => isset($_POST['pasaje_1_parque']) ? 1 : 0,
                'pasaje_2'          => isset($_POST['pasaje_2']) ? 1 : 0,
                'special_features'  => trim($_POST['special_features'] ?? ''),
                'notes'             => trim($_POST['notes'] ?? ''),
                'map_left'          => (float) ($_POST['map_left'] ?? 0.00),
                'map_top'           => (float) ($_POST['map_top'] ?? 0.00),
                'map_width'         => (float) ($_POST['map_width'] ?? 8.00),
                'map_height'        => (float) ($_POST['map_height'] ?? 8.00)
            ];

            $errors = [];
            if ($data['block_id'] <= 0) {
                $errors[] = 'Debes seleccionar una manzana válida';
            }
            if (empty($data['lot_number'])) {
                $errors[] = 'El número del lote es obligatorio';
            }
            if ($data['area'] <= 0) {
                $errors[] = 'El área debe ser mayor a 0';
            }
            if ($data['price'] <= 0) {
                $errors[] = 'El precio debe ser mayor a 0';
            }

            if (!empty($errors)) {
                $this->setFlash('danger', implode('<br>', $errors));
                $this->render('lots/form', [
                    'title'  => $title,
                    'data'   => $data,
                    'mode'   => $mode,
                    'id'     => $id,
                    'blocks' => $blocks
                ]);

                return;
            }

            if ($mode === 'create') {
                if ($this->lotModel->create($data)) {
                    $this->setFlash('success', 'Lote creado correctamente.');
                    $this->redirect('lots');
                } else {
                    $this->setFlash('danger', 'Error al crear el lote.');
                }
            } else {
                if ($this->lotModel->update($id, $data)) {
                    $this->setFlash('success', 'Lote actualizado correctamente.');
                    $this->redirect('lots');
                } else {
                    $this->setFlash('danger', 'Error al actualizar el lote.');
                }
            }
        }

        $this->render('lots/form', [
            'title'  => $title,
            'data'   => $data,
            'mode'   => $mode,
            'id'     => $id,
            'blocks' => $blocks
        ]);
    }

    public function toggleStatus(int $id)
    {
        $lot = $this->lotModel->getById($id);

        if (!$lot) {
            $this->setFlash('danger', 'Lote no encontrado.');
            $this->redirect('lots');
        }

        $newStatus = $lot['status'] === 'disponible' ? 'reservado' : 'disponible'; // ciclo simple, puedes mejorarlo

        if ($this->lotModel->changeStatus($id, $newStatus)) {
            $this->setFlash('success', 'Estado del lote <strong>' . htmlspecialchars($lot['lot_number']) . '</strong> cambiado a <strong>' . ucfirst($newStatus) . '</strong>.');
        } else {
            $this->setFlash('danger', 'Error al cambiar estado del lote.');
        }

        $this->redirect('lots');
    }

    public function map()
    {
        $blockId = (int) ($_GET['block_id'] ?? 0);

        if ($blockId > 0) {
            $block = $this->blockModel->getById($blockId);
            $lots  = $this->lotModel->getByBlock($blockId); // ya lo tienes
        } else {
            $block = null;
            $lots  = [];
        }

        $blocks = $this->blockModel->getAll();

        $this->render('lots/map', [
            'title'    => 'Mapa de Lotes',
            'blocks'   => $blocks,
            'block'    => $block,
            'lots'     => $lots,
            'block_id' => $blockId
        ]);
    }
}
