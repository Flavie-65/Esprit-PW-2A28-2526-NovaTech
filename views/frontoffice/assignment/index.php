<?php
/** @var list<Assignment> $items */
$pageTitle = 'Affectations';
ob_start();
?>
<div class="page-header">
    <h1>Historique des affectations</h1>
    <a class="btn" href="index.php?module=assignment&action=index">Administration</a>
</div>
<section class="panel">
    <table class="data-table">
        <thead>
            <tr>
                <th>Equipement</th>
                <th>Employe</th>
                <th>Debut</th>
                <th>Fin</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $row) : ?>
            <tr>
                <td><?= htmlspecialchars((string) ($row->getEquipmentName() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getEmployeeName(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getStartDate(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($row->getEndDate() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($items === []) : ?>
            <tr><td colspan="5">Aucune affectation a afficher.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
