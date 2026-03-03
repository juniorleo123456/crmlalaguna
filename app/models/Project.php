<?php
class Project
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT p.id, p.title, p.description, p.status, p.start_date, p.end_date, p.progress,
                   c.name AS client_name, u.name AS created_by_name
            FROM projects p
            LEFT JOIN users c ON p.client_id = c.id
            LEFT JOIN users u ON p.created_by = u.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM projects WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO projects (company_id, title, description, client_id, status, start_date, end_date, progress, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['company_id'] ?? null,
            $data['title'],
            $data['description'] ?? null,
            $data['client_id'] ?? null,
            $data['status'] ?? 'planificacion',
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['progress'] ?? 0,
            $data['created_by'] ?? $_SESSION['user_id']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE projects SET
                title = ?,
                description = ?,
                client_id = ?,
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
            $data['client_id'] ?? null,
            $data['status'],
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['progress'] ?? 0,
            $id
        ]);
    }

    public function toggleStatus(int $id, string $newStatus): bool
    {
        $stmt = $this->pdo->prepare("UPDATE projects SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $id]);
    }

    public function countActive(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'ejecucion'");
        return (int) $stmt->fetchColumn();
    }
}
