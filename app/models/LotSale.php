<?php
class LotSale
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function countPendingPayments(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM lot_sales WHERE payment_status IN ('atrasado', 'mora')");
        return (int) $stmt->fetchColumn();
    }

    public function getTotalMora(): float
    {
        $stmt = $this->pdo->query("SELECT SUM(late_fee) FROM lot_payments WHERE is_late = 1");
        return (float) $stmt->fetchColumn() ?: 0.0;
    }
}
