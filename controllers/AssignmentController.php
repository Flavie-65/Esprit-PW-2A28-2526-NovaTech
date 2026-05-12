<?php

declare(strict_types=1);

/**
 * Assignment HTTP layer: validation + manager calls + views. No SQL.
 */
class AssignmentController extends BaseController
{
    private const ALLOWED_STATUS = ['assigned', 'returned'];

    private AssignmentManager $assignmentManager;
    private EquipmentManager $equipmentManager;

    public function __construct()
    {
        $this->assignmentManager = new AssignmentManager();
        $this->equipmentManager = new EquipmentManager();
    }

    public function index(): void
    {
        $items = $this->assignmentManager->findAll();
        $flash = $this->consumeFlash();
        $scope = isset($_GET['scope']) ? (string) $_GET['scope'] : '';
        if ($scope === 'public') {
            $this->renderFrontOffice('assignment/index', ['items' => $items, 'flash' => $flash]);

            return;
        }

        $this->renderBackOffice('assignment/list', ['items' => $items, 'flash' => $flash]);
    }

    public function add(): void
    {
        $assignment = new Assignment();
        $this->renderBackOffice('assignment/form', [
            'assignment' => $assignment,
            'isEdit' => false,
            'errors' => [],
            'allowedStatuses' => self::ALLOWED_STATUS,
            'equipmentOptions' => $this->equipmentManager->findAll(),
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) {
            $this->redirectToList();

            return;
        }

        $assignment = $this->assignmentFromPost(false);
        $errors = $this->validateAssignment($assignment, false);
        if ($errors !== []) {
            $this->renderBackOffice('assignment/form', [
                'assignment' => $assignment,
                'isEdit' => false,
                'errors' => $errors,
                'allowedStatuses' => self::ALLOWED_STATUS,
                'equipmentOptions' => $this->equipmentManager->findAll(),
            ]);

            return;
        }

        try {
            $this->assignmentManager->insert($assignment);
            $this->flash('success', 'Affectation creee avec succes.');
        } catch (Throwable $e) {
            $this->renderBackOffice('assignment/form', [
                'assignment' => $assignment,
                'isEdit' => false,
                'errors' => [$e->getMessage()],
                'allowedStatuses' => self::ALLOWED_STATUS,
                'equipmentOptions' => $this->equipmentManager->findAll(),
            ]);

            return;
        }

        $this->redirectToList();
    }

    public function edit(): void
    {
        $id = $this->readIdFromGet();
        if ($id === null) {
            $this->notFound('Invalid id.');

            return;
        }

        $assignment = $this->assignmentManager->findById($id);
        if ($assignment === null) {
            $this->notFound('Assignment not found.');

            return;
        }

        $this->renderBackOffice('assignment/form', [
            'assignment' => $assignment,
            'isEdit' => true,
            'errors' => [],
            'allowedStatuses' => self::ALLOWED_STATUS,
            'equipmentOptions' => $this->equipmentManager->findAll(),
        ]);
    }

    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirectToList();

            return;
        }

        $assignment = $this->assignmentFromPost(true);
        $errors = $this->validateAssignment($assignment, true);
        if ($errors !== []) {
            $this->renderBackOffice('assignment/form', [
                'assignment' => $assignment,
                'isEdit' => true,
                'errors' => $errors,
                'allowedStatuses' => self::ALLOWED_STATUS,
                'equipmentOptions' => $this->equipmentManager->findAll(),
            ]);

            return;
        }

        try {
            $this->assignmentManager->update($assignment);
            $this->flash('success', 'Affectation mise a jour avec succes.');
        } catch (Throwable $e) {
            $this->renderBackOffice('assignment/form', [
                'assignment' => $assignment,
                'isEdit' => true,
                'errors' => [$e->getMessage()],
                'allowedStatuses' => self::ALLOWED_STATUS,
                'equipmentOptions' => $this->equipmentManager->findAll(),
            ]);

            return;
        }

        $this->redirectToList();
    }

    public function delete(): void
    {
        $id = $this->readIdFromGet();
        if ($id === null) {
            $this->notFound('Invalid id.');

            return;
        }

        try {
            $deleted = $this->assignmentManager->delete($id);
            $this->flash($deleted ? 'success' : 'error', $deleted ? 'Affectation supprimee.' : 'Affectation introuvable.');
        } catch (Throwable $e) {
            $this->flash('error', 'Impossible de supprimer cette affectation.');
        }

        $this->redirectToList();
    }

    private function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    private function readIdFromGet(): ?int
    {
        if (!isset($_GET['id'])) {
            return null;
        }

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        return $id !== false && $id > 0 ? $id : null;
    }

    private function readIdFromPost(): ?int
    {
        if (!isset($_POST['id'])) {
            return null;
        }

        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        return $id !== false && $id > 0 ? $id : null;
    }

    private function assignmentFromPost(bool $withId): Assignment
    {
        $a = new Assignment();
        if ($withId) {
            $a->setId($this->readIdFromPost());
        }

        $eqId = isset($_POST['equipment_id']) ? filter_var($_POST['equipment_id'], FILTER_VALIDATE_INT) : false;
        $a->setEquipmentId($eqId !== false && $eqId > 0 ? $eqId : null);

        $employeeName = isset($_POST['employee_name']) ? trim((string) $_POST['employee_name']) : '';
        $a->setEmployeeName($employeeName);

        $start = isset($_POST['start_date']) ? trim((string) $_POST['start_date']) : '';
        $a->setStartDate($start);

        $endRaw = isset($_POST['end_date']) ? trim((string) $_POST['end_date']) : '';
        $a->setEndDate($endRaw === '' ? null : $endRaw);

        $status = isset($_POST['status']) ? trim((string) $_POST['status']) : '';
        $a->setStatus($status);

        return $a;
    }

    /**
     * @return list<string>
     */
    private function validateAssignment(Assignment $assignment, bool $requireId): array
    {
        $errors = [];
        if ($requireId && ($assignment->getId() === null || $assignment->getId() < 1)) {
            $errors[] = 'Identifiant invalide.';
        } elseif ($requireId && !$this->assignmentManager->existsAssignment((int) $assignment->getId())) {
            $errors[] = 'Affectation introuvable.';
        }

        if ($assignment->getEquipmentId() === null || $assignment->getEquipmentId() < 1) {
            $errors[] = 'Equipement requis.';
        } elseif (!$this->equipmentManager->existsEquipment((int) $assignment->getEquipmentId())) {
            $errors[] = 'Equipement introuvable.';
        }

        if ($assignment->getEmployeeName() === '') {
            $errors[] = 'Le nom de l employe est obligatoire.';
        } elseif (mb_strlen($assignment->getEmployeeName()) > 255) {
            $errors[] = 'Le nom ne doit pas depasser 255 caracteres.';
        }

        if ($assignment->getStartDate() === '') {
            $errors[] = 'La date de debut est obligatoire.';
        } elseif (!$this->isValidDateYmd($assignment->getStartDate())) {
            $errors[] = 'Date de debut invalide (AAAA-MM-JJ).';
        }

        if ($assignment->getEndDate() !== null && $assignment->getEndDate() !== '' && !$this->isValidDateYmd((string) $assignment->getEndDate())) {
            $errors[] = 'Date de fin invalide (AAAA-MM-JJ).';
        }

        if (!in_array($assignment->getStatus(), self::ALLOWED_STATUS, true)) {
            $errors[] = 'Statut invalide.';
        }

        return $errors;
    }

    private function isValidDateYmd(string $value): bool
    {
        $d = DateTimeImmutable::createFromFormat('Y-m-d', $value);

        return $d !== false && $d->format('Y-m-d') === $value;
    }

    private function redirectToList(): void
    {
        header('Location: index.php?module=assignment&action=index', true, 302);
        exit;
    }

    private function notFound(string $message): void
    {
        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $message;
    }
}
