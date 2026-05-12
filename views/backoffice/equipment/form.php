<?php
/** @var Equipment $equipment */
/** @var bool $isEdit */
/** @var list<string> $errors */
/** @var list<string> $allowedStatuses */
$pageTitle = $isEdit ? 'Modifier equipement' : 'Ajouter equipement';
$actionUrl = $isEdit
    ? 'index.php?module=equipment&action=update'
    : 'index.php?module=equipment&action=store';
$statuses = $allowedStatuses ?? [];
ob_start();
?>
<div class="page-header">
    <h2><?= $isEdit ? 'Modifier' : 'Ajouter' ?> un equipement</h2>
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
        <?php if ($isEdit && $equipment->getId() !== null) : ?>
            <input type="hidden" name="id" value="<?= (int) $equipment->getId() ?>">
        <?php endif; ?>
        <label for="name">Nom</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($equipment->getName(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="category">Categorie</label>
        <input type="text" id="category" name="category"
               value="<?= htmlspecialchars($equipment->getCategory(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="serial_number">Numero de serie</label>
        <input type="text" id="serial_number" name="serial_number"
               value="<?= htmlspecialchars($equipment->getSerialNumber(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="purchase_date">Date d achat</label>
        <input type="text" id="purchase_date" name="purchase_date" placeholder="AAAA-MM-JJ"
               value="<?= htmlspecialchars((string) ($equipment->getPurchaseDate() ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <label for="status">Statut</label>
        <select id="status" name="status">
            <?php foreach ($statuses as $st) : ?>
                <option value="<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $equipment->getStatus() === $st ? 'selected' : '' ?>>
                    <?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="actions">
            <button type="submit"><?= $isEdit ? 'Enregistrer' : 'Creer' ?></button>
            <a class="btn secondary" href="index.php?module=equipment&action=index">Annuler</a>
        </div>
    </form>
</section>
<script>
document.querySelector('.js-validated-form').addEventListener('submit', function (event) {
    const errors = [];
    const fields = {
        name: 'Le nom est obligatoire.',
        category: 'La categorie est obligatoire.',
        serial_number: 'Le numero de serie est obligatoire.',
        purchase_date: 'La date d achat est obligatoire.'
    };
    Object.keys(fields).forEach(function (fieldName) {
        const field = document.querySelector('[name="' + fieldName + '"]');
        if (!field || field.value.trim() === '') {
            errors.push(fields[fieldName]);
        }
    });
    const purchaseDate = document.querySelector('[name="purchase_date"]').value.trim();
    if (purchaseDate !== '' && !/^\d{4}-\d{2}-\d{2}$/.test(purchaseDate)) {
        errors.push('La date d achat doit respecter le format AAAA-MM-JJ.');
    }
    const name = document.querySelector('[name="name"]').value.trim();
    const category = document.querySelector('[name="category"]').value.trim();
    const serial = document.querySelector('[name="serial_number"]').value.trim();
    if (name.length > 255) {
        errors.push('Le nom ne doit pas depasser 255 caracteres.');
    }
    if (category.length > 100) {
        errors.push('La categorie ne doit pas depasser 100 caracteres.');
    }
    if (serial.length > 100) {
        errors.push('Le numero de serie ne doit pas depasser 100 caracteres.');
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
