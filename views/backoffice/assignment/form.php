<?php
/** @var Assignment $assignment */
/** @var bool $isEdit */
/** @var list<string> $errors */
/** @var list<string> $allowedStatuses */
/** @var list<Equipment> $equipmentOptions */
$pageTitle = $isEdit ? 'Modifier affectation' : 'Ajouter affectation';
$actionUrl = $isEdit
    ? 'index.php?module=assignment&action=update'
    : 'index.php?module=assignment&action=store';
$statuses = $allowedStatuses ?? [];
ob_start();
?>
<div class="page-header">
    <h2><?= $isEdit ? 'Modifier' : 'Ajouter' ?> une affectation</h2>
</div>
<section class="form-card">
    <?php if ($errors !== []) : ?>
        <ul class="errors">
            <?php foreach ($errors as $err) : ?>
                <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" class="js-validated-form" novalidate>
        <ul class="errors js-errors" style="display:none;"></ul>
        <?php if ($isEdit && $assignment->getId() !== null) : ?>
            <input type="hidden" name="id" value="<?= (int) $assignment->getId() ?>">
        <?php endif; ?>

        <label for="equipment_id">Equipement</label>
        <select id="equipment_id" name="equipment_id">
            <option value="">Choisir</option>
            <?php foreach ($equipmentOptions as $eq) : ?>
                <option value="<?= (int) $eq->getId() ?>"
                    <?= (int) $assignment->getEquipmentId() === (int) $eq->getId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($eq->getName(), ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="employee_name">Employe (nom)</label>
        <input type="text" id="employee_name" name="employee_name"
               value="<?= htmlspecialchars($assignment->getEmployeeName(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="start_date">Date de debut</label>
        <input type="text" id="start_date" name="start_date" placeholder="AAAA-MM-JJ"
               value="<?= htmlspecialchars($assignment->getStartDate(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="end_date">Date de fin (optionnel)</label>
        <input type="text" id="end_date" name="end_date" placeholder="AAAA-MM-JJ"
               value="<?= htmlspecialchars((string) ($assignment->getEndDate() ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <label for="status">Statut</label>
        <select id="status" name="status">
            <?php foreach ($statuses as $st) : ?>
                <option value="<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $assignment->getStatus() === $st ? 'selected' : '' ?>>
                    <?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="actions">
            <button type="submit"><?= $isEdit ? 'Enregistrer' : 'Creer' ?></button>
            <a class="btn secondary" href="index.php?module=assignment&action=index">Annuler</a>
        </div>
    </form>
</section>
<script>
document.querySelector('.js-validated-form').addEventListener('submit', function (event) {
    const errors = [];
    const equipment = document.querySelector('[name="equipment_id"]').value;
    const employee = document.querySelector('[name="employee_name"]').value.trim();
    const startDate = document.querySelector('[name="start_date"]').value.trim();
    const endDate = document.querySelector('[name="end_date"]').value.trim();
    if (equipment === '') {
        errors.push('Equipement requis.');
    }
    if (employee === '') {
        errors.push('Le nom de l employe est obligatoire.');
    }
    if (employee.length > 255) {
        errors.push('Le nom ne doit pas depasser 255 caracteres.');
    }
    if (startDate === '') {
        errors.push('La date de debut est obligatoire.');
    }
    if (startDate !== '' && !/^\d{4}-\d{2}-\d{2}$/.test(startDate)) {
        errors.push('La date de debut doit respecter le format AAAA-MM-JJ.');
    }
    if (endDate !== '' && !/^\d{4}-\d{2}-\d{2}$/.test(endDate)) {
        errors.push('La date de fin doit respecter le format AAAA-MM-JJ.');
    }
    const box = document.querySelector('.js-errors');
    box.innerHTML = '';
    if (errors.length > 0) {
        event.preventDefault();
        errors.forEach(function (message) {
            const item = document.createElement('li');
            item.textContent = message;
            box.appendChild(item);
        });
        box.style.display = 'block';
    }
});
</script>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
