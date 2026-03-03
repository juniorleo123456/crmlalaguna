<?php
// app/controllers/ClientServicesController.php

class ClientServicesController extends Controller
{
    private ClientModel $clientModel;
    private ProjectModel $projectModel;

    public function __construct()
    {
        $this->requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('danger', 'Acceso restringido. Solo administradores pueden asociar servicios.');
            $this->redirect('dashboard');
        }

        $this->clientModel = new ClientModel(getDBConnection());
        $this->projectModel = new ProjectModel(getDBConnection());
    }

    public function create()
    {
        $clientId = (int) ($_GET['client_id'] ?? 0);

        if ($clientId <= 0) {
            $this->setFlash('danger', 'Cliente no especificado.');
            $this->redirect('clients');
        }

        $client = $this->clientModel->getById($clientId);
        if (!$client) {
            $this->setFlash('danger', 'Cliente no encontrado.');
            $this->redirect('clients');
        }

        $projects = $this->projectModel->getAll(); // o crea getActiveProjects() si quieres filtrar

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                $this->setFlash('danger', 'Error de validación de seguridad.');
                $this->redirect("client-services/create?client_id=$clientId");
            }

            $projectId = (int) ($_POST['project_id'] ?? 0);

            if ($projectId <= 0) {
                $this->setFlash('danger', 'Debes seleccionar un proyecto válido.');
            } else {
                // Verificar que no exista ya la asociación
                $check = getDBConnection()->prepare("
                    SELECT id FROM client_services 
                    WHERE client_id = ? AND project_id = ? AND service_id = 1
                ");
                $check->execute([$clientId, $projectId]);
                if ($check->fetch()) {
                    $this->setFlash('danger', 'Este proyecto ya está asociado a este cliente.');
                } else {
                    $stmt = getDBConnection()->prepare("
                        INSERT INTO client_services 
                        (client_id, service_id, project_id, status, start_date, created_at)
                        VALUES (?, 1, ?, 'active', CURDATE(), NOW())
                    ");
                    $stmt->execute([$clientId, $projectId]);

                    $this->setFlash('success', 'Proyecto asociado correctamente al cliente.');
                    $this->redirect("clients/view/$clientId");
                }
            }
        }

        $this->render('client-services/create', [
            'title'    => 'Asociar Proyecto a ' . $client['name'],
            'client'   => $client,
            'projects' => $projects
        ]);
    }
}
