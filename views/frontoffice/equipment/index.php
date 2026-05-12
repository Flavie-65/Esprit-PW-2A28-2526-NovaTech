<?php
/** @var list<Equipment> $items */
/** @var array{type: string, message: string}|null $flash */
$pageTitle = 'Equipements';
ob_start();
?>
<div class="page-header">
    <h1>Liste des equipements</h1>
    <a class="btn" href="index.php?module=equipment&action=index">Administration</a>
</div>
<section class="panel">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Numero de serie</th>
                <th>Statut</th>
                <th>Achat</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $row) : ?>
            <tr>
                <td><?= htmlspecialchars($row->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getCategory(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getSerialNumber(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($row->getPurchaseDate() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($items === []) : ?>
            <tr><td colspan="5">Aucun equipement a afficher.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
