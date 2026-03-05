<?php
// app/models/ProjectModel.php

class ProjectModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los proyectos
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT p.id, p.title, p.description, p.status, p.start_date, p.end_date, p.progress,
               u.name AS created_by_name
            FROM projects p
            LEFT JOIN users u ON p.created_by = u.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un proyecto por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
        SELECT p.*, u.name AS created_by_name
        FROM projects p
        LEFT JOIN users u ON p.created_by = u.id
        WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo proyecto
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO projects 
        (company_id, title, description, status, start_date, end_date, progress, created_by, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $data['company_id'] ?? null,
        $data['title'],
        $data['description'] ?? null,
        $data['status'] ?? 'planificacion',
        $data['start_date'] ?? null,
        $data['end_date'] ?? null,
        $data['progress'] ?? 0,
        $_SESSION['user_id'] ?? null
    ]);
    return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un proyecto
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE projects SET
            title = ?,
            description = ?,
            status = ?,
            start_date = ?,
            end_date = ?,
            progress = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    return $stmt->execute([
        $data['title'],
        $data['description'] ?? null,
        $data['status'],
        $data['start_date'] ?? null,
        $data['end_date'] ?? null,
        $data['progress'] ?? 0,
        $id
    ]);
    }

    /**
     * Cambia el estado del proyecto (planificacion → ejecucion → entregado → cancelado)
     */
    public function changeStatus(int $id, string $newStatus): bool
    {
        if (!in_array($newStatus, ['planificacion', 'ejecucion', 'entregado', 'cancelado'])) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE projects SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $id]);
    }

    public function countClientsForProject(int $projectId): int
    {
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM client_services WHERE project_id = ?");
    $stmt->execute([$projectId]);
    return (int) $stmt->fetchColumn();
    }

    /**
     * Obtiene solo clientes activos para el select del formulario
     */
    public function getActiveClients(): array
    {
        $stmt = $this->pdo->query("
        SELECT c.id, u.name, u.email
        FROM clients c
        JOIN users u ON c.user_id = u.id
        WHERE c.status = 'active'
        ORDER BY u.name ASC
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene proyectos con paginación, búsqueda y filtros
     * @param int $page Página actual (comienza en 1)
     * @param int $perPage Proyectos por página (default 10)
     * @param string $search Término de búsqueda (título o descripción)
     * @param string $status Filtro por estado (opcional)
     * @param int $clientId Filtro por cliente (opcional)
     * @return array Proyectos filtrados
     */
    public function getAllFiltered(int $page = 1, int $perPage = 10, string $search = '', string $status = ''): array
    {
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(p.title LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $where[] = "p.status = ?";
            $params[] = $status;
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare("
        SELECT p.id, p.title, p.description, p.status, p.start_date, p.end_date, p.progress,
               u.name AS created_by_name
        FROM projects p
        LEFT JOIN users u ON p.created_by = u.id
        $whereClause
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?
    ");
        $params[] = $perPage;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cuenta el total de proyectos con filtros
     * @return int Total filtrado
     */
    public function countFiltered(string $search = '', string $status = ''): int
    {
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $where[] = "status = ?";
            $params[] = $status;
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM projects $whereClause");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Obtiene los estados posibles para filtro
     */
    public function getStatuses(): array
    {
        return ['planificacion', 'ejecucion', 'entregado', 'cancelado'];
    }

    /**
 * Verifica si un proyecto existe por su ID
 * @param int $projectId ID del proyecto
 * @return bool true si existe, false si no
 */
public function projectExists(int $projectId): bool
{
    $stmt = $this->pdo->prepare("SELECT id FROM projects WHERE id = ? LIMIT 1");
    $stmt->execute([$projectId]);
    return (bool) $stmt->fetch();
}
}
