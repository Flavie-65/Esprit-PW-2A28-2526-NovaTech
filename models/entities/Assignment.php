<?php

declare(strict_types=1);

/**
 * Maps one row of the assignment table. No persistence logic.
 * equipmentName is set by AssignmentManager when listing assignment data.
 */
class Assignment
{
    private ?int $id = null;
    private ?int $equipmentId = null;
    private string $employeeName = '';
    private string $startDate = '';
    private ?string $endDate = null;
    private string $status = 'assigned';
    private ?string $createdAt = null;
    private ?string $equipmentName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEquipmentId(): ?int
    {
        return $this->equipmentId;
    }

    public function setEquipmentId(?int $equipmentId): void
    {
        $this->equipmentId = $equipmentId;
    }

    public function getEmployeeName(): string
    {
        return $this->employeeName;
    }

    public function setEmployeeName(string $employeeName): void
    {
        $this->employeeName = $employeeName;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getEquipmentName(): ?string
    {
        return $this->equipmentName;
    }

    public function setEquipmentName(?string $equipmentName): void
    {
        $this->equipmentName = $equipmentName;
    }
}
