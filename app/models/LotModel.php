<?php
// app/models/LotModel.php

class LotModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los lotes (con nombre de manzana y proyecto)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT l.id, l.block_id, l.lot_number, l.area, l.price, l.status,
                   l.is_corner, l.faces_park, l.faces_main_street,
                   b.name AS block_name,
                   p.title AS project_title
            FROM lots l
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            ORDER BY p.title, b.name, l.lot_number
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un lote por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT l.*, b.name AS block_name, p.title AS project_title
            FROM lots l
            LEFT JOIN blocks b ON l.block_id = b.id
            LEFT JOIN projects p ON b.project_id = p.id
            WHERE l.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo lote
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO lots 
    (block_id, lot_number, area, front, depth, price, status, 
     is_corner, faces_park, faces_main_street, 
     jiron_principal, calle_1, calle_2, pasaje_1_parque, pasaje_2,
     special_features, notes, 
     map_left, map_top, map_width, map_height, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['block_id'],
            $data['lot_number'],
            $data['area'],
            $data['front'] ?? null,
            $data['depth'] ?? null,
            $data['price'],
            $data['status'] ?? 'disponible',
            $data['is_corner'] ?? 0,
            $data['faces_park'] ?? 0,
            $data['faces_main_street'] ?? 0,
            $data['jiron_principal'] ?? 0,
            $data['calle_1'] ?? 0,
            $data['calle_2'] ?? 0,
            $data['pasaje_1_parque'] ?? 0,
            $data['pasaje_2'] ?? 0,
            $data['special_features'] ?? null,
            $data['notes'] ?? null,
            $data['map_left'] ,
            $data['map_top'] ,
            $data['map_width'] ,
            $data['map_height']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un lote
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE lots SET
                block_id = ?,
                lot_number = ?,
                area = ?,
                front = ?,
                depth = ?,
                price = ?,
                status = ?,
                is_corner = ?,
                faces_park = ?,
                faces_main_street = ?,
                jiron_principal = ?,
                calle_1 = ?,
                calle_2 = ?,
                pasaje_1_parque = ?,
                pasaje_2 = ?,
                special_features = ?,
                notes = ?,
                map_left = ?,
                map_top = ?,
                map_width = ?,
                map_height = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['block_id'],
            $data['lot_number'],
            $data['area'],
            $data['front'] ?? null,
            $data['depth'] ?? null,
            $data['price'],
            $data['status'],
            $data['is_corner'] ?? 0,
            $data['faces_park'] ?? 0,
            $data['faces_main_street'] ?? 0,
            $data['jiron_principal'] ?? 0,
            $data['calle_1'] ?? 0,
            $data['calle_2'] ?? 0,
            $data['pasaje_1_parque'] ?? 0,
            $data['pasaje_2'] ?? 0,
            $data['special_features'] ?? null,
            $data['notes'] ?? null,
            $data['map_left'],
            $data['map_top'],
            $data['map_width'],
            $data['map_height'],
            $id
        ]);
    }

    /**
 * Lista lotes con paginación y filtros
 */
public function getAllFiltered(int $page = 1, int $perPage = 10, int $blockId = 0, string $status = ''): array
{
    $where = [];
    $params = [];

    if ($blockId > 0) {
        $where[] = "l.block_id = ?";
        $params[] = $blockId;
    }

    if (!empty($status)) {
        $where[] = "l.status = ?";
        $params[] = $status;
    }

    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $offset = ($page - 1) * $perPage;

    $stmt = $this->pdo->prepare("
        SELECT l.id, l.block_id, l.lot_number, l.area, l.price, l.status,
               l.is_corner, l.faces_park, l.faces_main_street,
               l.jiron_principal, l.calle_1, l.calle_2, l.pasaje_1_parque, l.pasaje_2,
               b.name AS block_name,
               p.title AS project_title
        FROM lots l
        LEFT JOIN blocks b ON l.block_id = b.id
        LEFT JOIN projects p ON b.project_id = p.id
        $whereClause
        ORDER BY p.title, b.name, l.lot_number
        LIMIT ? OFFSET ?
    ");
    $params[] = $perPage;
    $params[] = $offset;
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Cuenta total de lotes con filtros (para calcular páginas)
 */
public function countFiltered(int $blockId = 0, string $status = ''): int
{
    $where = [];
    $params = [];

    if ($blockId > 0) {
        $where[] = "block_id = ?";
        $params[] = $blockId;
    }

    if (!empty($status)) {
        $where[] = "status = ?";
        $params[] = $status;
    }

    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM lots l
        $whereClause
    ");
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

    /**
     * Cambia el estado del lote (ciclo simple o directo)
     */
    public function changeStatus(int $id, string $newStatus): bool
    {
        if (!in_array($newStatus, ['disponible', 'reservado', 'vendido', 'mora', 'cancelado'])) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE lots SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $id]);
    }

    public function getByBlock(int $blockId): array
{
    $stmt = $this->pdo->prepare("
        SELECT * FROM lots 
        WHERE block_id = ? 
        ORDER BY lot_number ASC
    ");
    $stmt->execute([$blockId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
