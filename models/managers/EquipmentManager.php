<?php

declare(strict_types=1);

/**
 * All persistence for equipment. Only layer that runs SQL on this table.
 */
class EquipmentManager extends BaseManager
{
    private const TABLE = 'equipment';

    /**
     * @return list<Equipment>
     */
    public function findAll(): array
    {
        $sql = 'SELECT id, name, category, serial_number, status, purchase_date, created_at FROM ' . self::TABLE . ' ORDER BY id ASC';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->hydrate($row);
        }

        return $list;
    }

    public function findById(int $id): ?Equipment
    {
        $sql = 'SELECT id, name, category, serial_number, status, purchase_date, created_at FROM ' . self::TABLE . ' WHERE id = :id LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function insert(Equipment $equipment): int
    {
        $createdAt = $equipment->getCreatedAt() ?? date('Y-m-d H:i:s');
        $sql = 'INSERT INTO ' . self::TABLE . ' (name, category, serial_number, status, purchase_date, created_at) '
            . 'VALUES (:name, :category, :serial_number, :status, :purchase_date, :created_at)';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':name', $equipment->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':category', $equipment->getCategory(), PDO::PARAM_STR);
        $stmt->bindValue(':serial_number', $equipment->getSerialNumber(), PDO::PARAM_STR);
        $stmt->bindValue(':status', $equipment->getStatus(), PDO::PARAM_STR);
        if ($equipment->getPurchaseDate() === null || $equipment->getPurchaseDate() === '') {
            $stmt->bindValue(':purchase_date', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':purchase_date', $equipment->getPurchaseDate(), PDO::PARAM_STR);
        }
        $stmt->bindValue(':created_at', $createdAt, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $this->getPdo()->lastInsertId();
    }

    public function update(Equipment $equipment): bool
    {
        $id = $equipment->getId();
        if ($id === null) {
            return false;
        }

        $sql = 'UPDATE ' . self::TABLE . ' SET name = :name, category = :category, serial_number = :serial_number, '
            . 'status = :status, purchase_date = :purchase_date WHERE id = :id';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':name', $equipment->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':category', $equipment->getCategory(), PDO::PARAM_STR);
        $stmt->bindValue(':serial_number', $equipment->getSerialNumber(), PDO::PARAM_STR);
        $stmt->bindValue(':status', $equipment->getStatus(), PDO::PARAM_STR);
        if ($equipment->getPurchaseDate() === null || $equipment->getPurchaseDate() === '') {
            $stmt->bindValue(':purchase_date', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':purchase_date', $equipment->getPurchaseDate(), PDO::PARAM_STR);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM ' . self::TABLE . ' WHERE id = :id';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function deleteById(int $id): bool
    {
        return $this->delete($id);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = 'UPDATE ' . self::TABLE . ' SET status = :status WHERE id = :id';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function existsEquipment(int $id): bool
    {
        $sql = 'SELECT 1 FROM ' . self::TABLE . ' WHERE id = :id LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * @return array{total: int, available: int, assigned: int, maintenance: int, damaged: int}
     */
    public function getDashboardStats(): array
    {
        $sql = "SELECT "
            . "COUNT(*) AS total, "
            . "SUM(CASE WHEN LOWER(status) = 'available' THEN 1 ELSE 0 END) AS available, "
            . "SUM(CASE WHEN LOWER(status) = 'assigned' THEN 1 ELSE 0 END) AS assigned, "
            . "SUM(CASE WHEN LOWER(status) = 'maintenance' THEN 1 ELSE 0 END) AS maintenance, "
            . "SUM(CASE WHEN LOWER(status) = 'damaged' THEN 1 ELSE 0 END) AS damaged "
            . "FROM " . self::TABLE;
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total' => (int) ($row['total'] ?? 0),
            'available' => (int) ($row['available'] ?? 0),
            'assigned' => (int) ($row['assigned'] ?? 0),
            'maintenance' => (int) ($row['maintenance'] ?? 0),
            'damaged' => (int) ($row['damaged'] ?? 0),
        ];
    }

    /**
     * @return array<int, array{id: int, name: string, score: int, level: string, prediction: string, reason: string, assignment_count: int, active_days: int}>
     */
    public function getEquipmentHealthScores(): array
    {
        $sql = 'SELECT e.id, e.name, e.status, e.purchase_date, '
            . 'COUNT(a.id) AS assignment_count, '
            . "MAX(CASE WHEN LOWER(a.status) = 'assigned' THEN DATEDIFF(CURDATE(), a.start_date) ELSE 0 END) AS active_days "
            . 'FROM ' . self::TABLE . ' e '
            . 'LEFT JOIN assignment a ON a.equipment_id = e.id '
            . 'GROUP BY e.id, e.name, e.status, e.purchase_date '
            . 'ORDER BY e.id ASC';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $scores = [];
        foreach ($rows as $row) {
            $health = $this->calculateHealthScore($row);
            $scores[(int) $row['id']] = $health;
        }

        return $scores;
    }

    /**
     * @return array{average: int, risky: int, critical: int, top_risks: list<array{id: int, name: string, score: int, level: string, prediction: string, reason: string, assignment_count: int, active_days: int}>}
     */
    public function getHealthDashboardSummary(): array
    {
        $scores = array_values($this->getEquipmentHealthScores());
        if ($scores === []) {
            return [
                'average' => 0,
                'risky' => 0,
                'critical' => 0,
                'top_risks' => [],
            ];
        }

        $total = 0;
        $risky = 0;
        $critical = 0;
        foreach ($scores as $score) {
            $total += $score['score'];
            if (in_array($score['level'], ['Risk', 'Critical'], true)) {
                $risky++;
            }
            if ($score['level'] === 'Critical') {
                $critical++;
            }
        }

        usort($scores, static function (array $a, array $b): int {
            return $a['score'] <=> $b['score'];
        });

        return [
            'average' => (int) round($total / count($scores)),
            'risky' => $risky,
            'critical' => $critical,
            'top_risks' => array_slice($scores, 0, 5),
        ];
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Equipment
    {
        $e = new Equipment();
        $e->setId(isset($row['id']) ? (int) $row['id'] : null);
        $e->setName((string) ($row['name'] ?? ''));
        $e->setCategory((string) ($row['category'] ?? ''));
        $e->setSerialNumber((string) ($row['serial_number'] ?? ''));
        $e->setStatus(strtolower((string) ($row['status'] ?? 'available')));
        $purchase = $row['purchase_date'] ?? null;
        $e->setPurchaseDate($purchase !== null && $purchase !== '' ? $this->normalizeDateString($purchase) : null);
        $created = $row['created_at'] ?? null;
        $e->setCreatedAt($created !== null && $created !== '' ? (string) $created : null);

        return $e;
    }

    /**
     * @param mixed $value
     */
    private function normalizeDateString($value): string
    {
        $s = (string) $value;

        return strlen($s) >= 10 ? substr($s, 0, 10) : $s;
    }

    /**
     * @param array<string, mixed> $row
     * @return array{id: int, name: string, score: int, level: string, prediction: string, reason: string, assignment_count: int, active_days: int}
     */
    private function calculateHealthScore(array $row): array
    {
        $score = 100;
        $reasons = [];
        $status = strtolower((string) ($row['status'] ?? 'available'));
        $assignmentCount = (int) ($row['assignment_count'] ?? 0);
        $activeDays = (int) ($row['active_days'] ?? 0);
        $ageMonths = $this->equipmentAgeMonths($row['purchase_date'] ?? null);

        if ($ageMonths >= 48) {
            $score -= 35;
            $reasons[] = 'very old equipment';
        } elseif ($ageMonths >= 36) {
            $score -= 25;
            $reasons[] = 'older than 3 years';
        } elseif ($ageMonths >= 24) {
            $score -= 15;
            $reasons[] = 'older than 2 years';
        } elseif ($ageMonths >= 12) {
            $score -= 8;
            $reasons[] = 'older than 1 year';
        }

        if ($assignmentCount >= 20) {
            $score -= 30;
            $reasons[] = 'very high assignment usage';
        } elseif ($assignmentCount >= 10) {
            $score -= 20;
            $reasons[] = 'high assignment usage';
        } elseif ($assignmentCount >= 5) {
            $score -= 10;
            $reasons[] = 'repeated assignment usage';
        }

        if ($activeDays >= 90) {
            $score -= 25;
            $reasons[] = 'active assignment over 90 days';
        } elseif ($activeDays >= 30) {
            $score -= 10;
            $reasons[] = 'active assignment over 30 days';
        }

        if ($status === 'damaged') {
            $score -= 60;
            $reasons[] = 'marked damaged';
        } elseif ($status === 'maintenance') {
            $score -= 35;
            $reasons[] = 'currently in maintenance';
        } elseif ($status === 'assigned') {
            $score -= 5;
            $reasons[] = 'currently assigned';
        }

        $score = max(0, min(100, $score));
        $level = $this->riskLevelFromScore($score);

        return [
            'id' => (int) ($row['id'] ?? 0),
            'name' => (string) ($row['name'] ?? ''),
            'score' => $score,
            'level' => $level,
            'prediction' => $this->maintenancePrediction($score, $status),
            'reason' => $reasons === [] ? 'No risk signals detected' : implode(', ', $reasons),
            'assignment_count' => $assignmentCount,
            'active_days' => $activeDays,
        ];
    }

    /**
     * @param mixed $purchaseDate
     */
    private function equipmentAgeMonths($purchaseDate): int
    {
        if ($purchaseDate === null || $purchaseDate === '') {
            return 0;
        }

        try {
            $purchase = new DateTimeImmutable(substr((string) $purchaseDate, 0, 10));
            $now = new DateTimeImmutable('today');
            $diff = $purchase->diff($now);

            return ($diff->y * 12) + $diff->m;
        } catch (Throwable $e) {
            return 0;
        }
    }

    private function riskLevelFromScore(int $score): string
    {
        if ($score >= 80) {
            return 'Healthy';
        }
        if ($score >= 50) {
            return 'Watch';
        }
        if ($score >= 25) {
            return 'Risk';
        }

        return 'Critical';
    }

    private function maintenancePrediction(int $score, string $status): string
    {
        if ($status === 'damaged') {
            return 'Immediate intervention required';
        }
        if ($status === 'maintenance') {
            return 'Already in maintenance';
        }
        if ($score < 25) {
            return 'Maintenance required now';
        }
        if ($score < 50) {
            return 'Maintenance recommended within 30 days';
        }
        if ($score < 80) {
            return 'Review recommended within 90 days';
        }

        return 'No maintenance predicted soon';
    }
}
