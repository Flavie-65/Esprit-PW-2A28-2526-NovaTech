<?php

declare(strict_types=1);

/**
 * Equipment HTTP layer: validation + manager calls + views. No SQL.
 */
class EquipmentController extends BaseController
{
    private const ALLOWED_STATUS = ['available', 'assigned', 'maintenance'];

    private EquipmentManager $equipmentManager;

    public function __construct()
    {
        $this->equipmentManager = new EquipmentManager();
    }

    public function index(): void
    {
        $items = $this->equipmentManager->findAll();
        $flash = $this->consumeFlash();
        $scope = isset($_GET['scope']) ? (string) $_GET['scope'] : '';
        if ($scope === 'public') {
            $this->renderFrontOffice('equipment/index', ['items' => $items, 'flash' => $flash]);

            return;
        }

        $this->renderBackOffice('equipment/list', [
            'items' => $items,
            'flash' => $flash,
            'healthScores' => $this->equipmentManager->getEquipmentHealthScores(),
        ]);
    }

    public function add(): void
    {
        $equipment = new Equipment();
        $this->renderBackOffice('equipment/form', [
            'equipment' => $equipment,
            'isEdit' => false,
            'errors' => [],
            'allowedStatuses' => self::ALLOWED_STATUS,
        ]);
    }

    public function store(): void
    {
        if (!$this->isPost()) {
            $this->redirectToList();

            return;
        }

        $equipment = $this->equipmentFromPost(false);
        $errors = $this->validateEquipment($equipment, false);
        if ($errors !== []) {
            $this->renderBackOffice('equipment/form', [
                'equipment' => $equipment,
                'isEdit' => false,
                'errors' => $errors,
                'allowedStatuses' => self::ALLOWED_STATUS,
            ]);

            return;
        }

        $equipment->setCreatedAt(date('Y-m-d H:i:s'));
        $this->equipmentManager->insert($equipment);
        $this->flash('success', 'Equipement cree avec succes.');
        $this->redirectToList();
    }

    public function edit(): void
    {
        $id = $this->readIdFromGet();
        if ($id === null) {
            $this->notFound('Invalid id.');

            return;
        }

        $equipment = $this->equipmentManager->findById($id);
        if ($equipment === null) {
            $this->notFound('Equipment not found.');

            return;
        }

        $this->renderBackOffice('equipment/form', [
            'equipment' => $equipment,
            'isEdit' => true,
            'errors' => [],
            'allowedStatuses' => self::ALLOWED_STATUS,
        ]);
    }

    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirectToList();

            return;
        }

        $equipment = $this->equipmentFromPost(true);
        $errors = $this->validateEquipment($equipment, true);
        if ($errors !== []) {
            $this->renderBackOffice('equipment/form', [
                'equipment' => $equipment,
                'isEdit' => true,
                'errors' => $errors,
                'allowedStatuses' => self::ALLOWED_STATUS,
            ]);

            return;
        }

        if (!$this->equipmentManager->existsEquipment((int) $equipment->getId())) {
            $this->flash('error', 'Equipement introuvable.');
            $this->redirectToList();

            return;
        }

        $this->equipmentManager->update($equipment);
        $this->flash('success', 'Equipement mis a jour avec succes.');
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
            $deleted = $this->equipmentManager->delete($id);
            $this->flash($deleted ? 'success' : 'error', $deleted ? 'Equipement supprime.' : 'Equipement introuvable.');
        } catch (Throwable $e) {
            $this->flash('error', 'Impossible de supprimer cet equipement : il est lie a une affectation.');
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

    private function equipmentFromPost(bool $withId): Equipment
    {
        $e = new Equipment();
        if ($withId) {
            $e->setId($this->readIdFromPost());
        }

        $name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
        $category = isset($_POST['category']) ? trim((string) $_POST['category']) : '';
        $serialNumber = isset($_POST['serial_number']) ? trim((string) $_POST['serial_number']) : '';
        $status = isset($_POST['status']) ? trim((string) $_POST['status']) : '';
        $purchaseDate = isset($_POST['purchase_date']) ? trim((string) $_POST['purchase_date']) : '';

        $e->setName($name);
        $e->setCategory($category);
        $e->setSerialNumber($serialNumber);
        $e->setStatus($status);
        $e->setPurchaseDate($purchaseDate === '' ? null : $purchaseDate);

        return $e;
    }

    /**
     * @return list<string>
     */
    private function validateEquipment(Equipment $equipment, bool $requireId): array
    {
        $errors = [];
        if ($requireId && ($equipment->getId() === null || $equipment->getId() < 1)) {
            $errors[] = 'Identifiant invalide.';
        }

        if ($equipment->getName() === '') {
            $errors[] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($equipment->getName()) > 255) {
            $errors[] = 'Le nom ne doit pas depasser 255 caracteres.';
        }

        if ($equipment->getCategory() === '') {
            $errors[] = 'La categorie est obligatoire.';
        } elseif (mb_strlen($equipment->getCategory()) > 100) {
            $errors[] = 'La categorie ne doit pas depasser 100 caracteres.';
        }

        if ($equipment->getSerialNumber() === '') {
            $errors[] = 'Le numero de serie est obligatoire.';
        } elseif (mb_strlen($equipment->getSerialNumber()) > 100) {
            $errors[] = 'Le numero de serie ne doit pas depasser 100 caracteres.';
        }

        if ($equipment->getPurchaseDate() === null || $equipment->getPurchaseDate() === '') {
            $errors[] = 'La date d achat est obligatoire.';
        } elseif (!$this->isValidDateYmd($equipment->getPurchaseDate())) {
            $errors[] = 'Date d achat invalide (AAAA-MM-JJ).';
        }

        if (!in_array($equipment->getStatus(), self::ALLOWED_STATUS, true)) {
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
        header('Location: index.php?module=equipment&action=index', true, 302);
        exit;
    }

    private function notFound(string $message): void
    {
        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $message;
    }
}
