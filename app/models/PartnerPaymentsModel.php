<?php
// app/models/PartnerPaymentsModel.php

class PartnerPaymentsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos los pagos mensuales a socios
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                pp.*,
                s.nombre_empresa,
                u.name AS socio_name,
                u.email AS socio_email,
                registrar.name AS registered_by_name
            FROM partner_payments pp
            JOIN socios s ON pp.socio_id = s.id
            JOIN users u ON s.user_id = u.id
            LEFT JOIN users registrar ON pp.registered_by = registrar.id
            ORDER BY pp.periodo DESC, pp.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo pago mensual a socio
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO partner_payments 
            (socio_id, periodo, total_ingresos_mes, monto_pago, tipo_comision, 
             porcentaje, monto_fijo, notes, registered_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $data['socio_id'],
            $data['periodo'],
            $data['total_ingresos_mes'],
            $data['monto_pago'],
            $data['tipo_comision'],
            $data['porcentaje'] ?? null,
            $data['monto_fijo'] ?? null,
            $data['notes'] ?? null,
            $_SESSION['user_id'] ?? null
        ]);

        return (int) $this->pdo->lastInsertId();
    }
        /**
     * Obtiene un pago por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM partner_payments WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Actualiza un pago existente
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE partner_payments SET
                socio_id           = ?,
                periodo            = ?,
                total_ingresos_mes = ?,
                monto_pago         = ?,
                tipo_comision      = ?,
                porcentaje         = ?,
                monto_fijo         = ?,
                notes              = ?,
                updated_at         = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['socio_id'],
            $data['periodo'],
            $data['total_ingresos_mes'],
            $data['monto_pago'],
            $data['tipo_comision'],
            $data['porcentaje'] ?? null,
            $data['monto_fijo']   ?? null,
            $data['notes']        ?? null,
            $id
        ]);
    }
}