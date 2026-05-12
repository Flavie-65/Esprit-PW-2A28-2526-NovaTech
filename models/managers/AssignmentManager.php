<?php

declare(strict_types=1);

/**
 * All persistence for assignment. Only layer that runs SQL on this table.
 */
class AssignmentManager extends BaseManager
{
    private const TABLE = 'assignment';

    /**
     * @return list<Assignment>
     */
    public function findAll(): array
    {
        $sql = 'SELECT a.id, a.equipment_id, a.employee_name, a.start_date, a.end_date, a.status, a.created_at, e.name AS equipment_name '
            . 'FROM ' . self::TABLE . ' a '
            . 'INNER JOIN equipment e ON e.id = a.equipment_id '
            . 'ORDER BY a.id ASC';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->hydrate($row);
        }

        return $list;
    }

    public function findById(int $id): ?Assignment
    {
        $sql = 'SELECT a.id, a.equipment_id, a.employee_name, a.start_date, a.end_date, a.status, a.created_at, e.name AS equipment_name '
            . 'FROM ' . self::TABLE . ' a '
            . 'INNER JOIN equipment e ON e.id = a.equipment_id '
            . 'WHERE a.id = :id LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function insert(Assignment $assignment): int
    {
        $pdo = $this->getPdo();
        $pdo->beginTransaction();
        try {
            $equipmentId = (int) $assignment->getEquipmentId();
            if (!$this->equipmentExistsForUpdate($equipmentId)) {
                throw new RuntimeException('Equipement introuvable.');
            }
            if ($assignment->getStatus() === 'assigned' && !$this->equipmentIsAvailable($equipmentId)) {
                throw new RuntimeException('Cet equipement n est pas disponible.');
            }

            $createdAt = $assignment->getCreatedAt() ?? date('Y-m-d H:i:s');
            $sql = 'INSERT INTO ' . self::TABLE . ' (equipment_id, employee_name, start_date, end_date, status, created_at) '
                . 'VALUES (:equipment_id, :employee_name, :start_date, :end_date, :status, :created_at)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':equipment_id', $assignment->getEquipmentId(), PDO::PARAM_INT);
            $stmt->bindValue(':employee_name', $assignment->getEmployeeName(), PDO::PARAM_STR);
            $stmt->bindValue(':start_date', $assignment->getStartDate(), PDO::PARAM_STR);
            if ($assignment->getEndDate() === null || $assignment->getEndDate() === '') {
                $stmt->bindValue(':end_date', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':end_date', $assignment->getEndDate(), PDO::PARAM_STR);
            }
            $stmt->bindValue(':status', $assignment->getStatus(), PDO::PARAM_STR);
            $stmt->bindValue(':created_at', $createdAt, PDO::PARAM_STR);
            $stmt->execute();

            $newId = (int) $pdo->lastInsertId();
            $this->syncEquipmentStatus($equipmentId, $assignment->getStatus());
            $pdo->commit();

            return $newId;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function update(Assignment $assignment): bool
    {
        $id = $assignment->getId();
        if ($id === null) {
            return false;
        }

        $pdo = $this->getPdo();
        $pdo->beginTransaction();
        try {
            $old = $this->findRawByIdForUpdate($id);
            if ($old === null) {
                throw new RuntimeException('Affectation introuvable.');
            }

            $equipmentId = (int) $assignment->getEquipmentId();
            if (!$this->equipmentExistsForUpdate($equipmentId)) {
                throw new RuntimeException('Equipement introuvable.');
            }

            $oldEquipmentId = (int) $old['equipment_id'];
            $sameActiveEquipment = $oldEquipmentId === $equipmentId && (string) $old['status'] === 'assigned';
            if ($assignment->getStatus() === 'assigned' && !$sameActiveEquipment && !$this->equipmentIsAvailable($equipmentId)) {
                throw new RuntimeException('Cet equipement n est pas disponible.');
            }

            $sql = 'UPDATE ' . self::TABLE . ' SET equipment_id = :equipment_id, employee_name = :employee_name, '
                . 'start_date = :start_date, end_date = :end_date, status = :status WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':equipment_id', $assignment->getEquipmentId(), PDO::PARAM_INT);
            $stmt->bindValue(':employee_name', $assignment->getEmployeeName(), PDO::PARAM_STR);
            $stmt->bindValue(':start_date', $assignment->getStartDate(), PDO::PARAM_STR);
            if ($assignment->getEndDate() === null || $assignment->getEndDate() === '') {
                $stmt->bindValue(':end_date', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':end_date', $assignment->getEndDate(), PDO::PARAM_STR);
            }
            $stmt->bindValue(':status', $assignment->getStatus(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($oldEquipmentId !== $equipmentId || $assignment->getStatus() === 'returned') {
                $this->syncEquipmentStatus($oldEquipmentId, 'returned');
            }
            $this->syncEquipmentStatus($equipmentId, $assignment->getStatus());
            $pdo->commit();

            return $stmt->rowCount() > 0;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $pdo = $this->getPdo();
        $pdo->beginTransaction();
        try {
            $old = $this->findRawByIdForUpdate($id);
            if ($old === null) {
                $pdo->commit();

                return false;
            }

            $sql = 'DELETE FROM ' . self::TABLE . ' WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ((string) $old['status'] === 'assigned') {
                $this->syncEquipmentStatus((int) $old['equipment_id'], 'returned');
            }
            $pdo->commit();

            return $stmt->rowCount() > 0;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function deleteById(int $id): bool
    {
        return $this->delete($id);
    }

    public function existsAssignment(int $id): bool
    {
        $sql = 'SELECT 1 FROM ' . self::TABLE . ' WHERE id = :id LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * @return array{total: int, active: int, returned: int}
     */
    public function getDashboardStats(): array
    {
        $sql = "SELECT "
            . "COUNT(*) AS total, "
            . "SUM(CASE WHEN LOWER(status) = 'assigned' THEN 1 ELSE 0 END) AS active, "
            . "SUM(CASE WHEN LOWER(status) = 'returned' THEN 1 ELSE 0 END) AS returned "
            . "FROM " . self::TABLE;
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total' => (int) ($row['total'] ?? 0),
            'active' => (int) ($row['active'] ?? 0),
            'returned' => (int) ($row['returned'] ?? 0),
        ];
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Assignment
    {
        $a = new Assignment();
        $a->setId(isset($row['id']) ? (int) $row['id'] : null);
        $a->setEquipmentId(isset($row['equipment_id']) ? (int) $row['equipment_id'] : null);
        $a->setEmployeeName((string) ($row['employee_name'] ?? ''));
        $a->setStartDate($this->normalizeDateString($row['start_date'] ?? null));
        $end = $row['end_date'] ?? null;
        $a->setEndDate($end !== null && $end !== '' ? $this->normalizeDateString($end) : null);
        $a->setStatus((string) ($row['status'] ?? 'assigned'));
        $created = $row['created_at'] ?? null;
        $a->setCreatedAt($created !== null && $created !== '' ? (string) $created : null);
        $name = $row['equipment_name'] ?? null;
        $a->setEquipmentName($name !== null && $name !== '' ? (string) $name : null);

        return $a;
    }

    /**
     * @param mixed $value
     */
    private function normalizeDateString($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        $s = (string) $value;

        return strlen($s) >= 10 ? substr($s, 0, 10) : $s;
    }

    private function equipmentExistsForUpdate(int $equipmentId): bool
    {
        $sql = 'SELECT 1 FROM equipment WHERE id = :id LIMIT 1 FOR UPDATE';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $equipmentId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    private function equipmentIsAvailable(int $equipmentId): bool
    {
        $sql = 'SELECT status FROM equipment WHERE id = :id LIMIT 1';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $equipmentId, PDO::PARAM_INT);
        $stmt->execute();
        $status = $stmt->fetchColumn();

        return is_string($status) && strtolower($status) === 'available';
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findRawByIdForUpdate(int $id): ?array
    {
        $sql = 'SELECT id, equipment_id, status FROM ' . self::TABLE . ' WHERE id = :id LIMIT 1 FOR UPDATE';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $row : null;
    }

    private function syncEquipmentStatus(int $equipmentId, string $assignmentStatus): void
    {
        $status = $assignmentStatus === 'assigned' ? 'assigned' : 'available';
        $sql = 'UPDATE equipment SET status = :status WHERE id = :id';
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $equipmentId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
