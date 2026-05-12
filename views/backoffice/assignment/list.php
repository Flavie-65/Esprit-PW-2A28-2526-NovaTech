<?php
/** @var list<Assignment> $items */
/** @var array{type: string, message: string}|null $flash */
$pageTitle = 'Affectations';
ob_start();
?>
<div class="page-header">
    <h2>Affectations</h2>
    <a class="btn" href="index.php?module=assignment&action=add">Ajouter</a>
</div>
<?php if (!empty($flash)) : ?>
    <p class="flash <?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>
<div class="toolbar">
    <a class="btn secondary" href="index.php?module=assignment&action=index&scope=public">Vue publique</a>
    <a class="btn secondary" href="index.php?module=equipment&action=index">Equipements</a>
</div>
<section class="panel">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Equipement</th>
                <th>Employe</th>
                <th>Debut</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $row) : ?>
            <tr>
                <td><?= htmlspecialchars((string) $row->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($row->getEquipmentName() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getEmployeeName(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getStartDate(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($row->getEndDate() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <div class="table-actions">
                        <a href="index.php?module=assignment&action=edit&amp;id=<?= (int) $row->getId() ?>">Modifier</a>
                        <a href="index.php?module=assignment&action=delete&amp;id=<?= (int) $row->getId() ?>" onclick="return confirm('Supprimer cette affectation ?');">Supprimer</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($items === []) : ?>
            <tr><td colspan="7">Aucune affectation.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
