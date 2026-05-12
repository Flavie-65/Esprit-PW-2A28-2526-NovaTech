<?php
/** @var list<Equipment> $items */
/** @var array{type: string, message: string}|null $flash */
/** @var array<int, array{id: int, name: string, score: int, level: string, prediction: string, reason: string, assignment_count: int, active_days: int}> $healthScores */
$pageTitle = 'Equipements';
ob_start();
?>
<style>
    .health-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.28rem 0.5rem; border-radius: 999px; font-weight: 800; font-size: 0.82rem; background: #eaf8f3; color: #127455; }
    .health-badge.watch { background: #fff4d6; color: #8a5b00; }
    .health-badge.risk { background: #fff0df; color: #965000; }
    .health-badge.critical { background: #ffe8e8; color: #a12727; }
    .health-note { display: block; margin-top: 0.25rem; color: #64716d; font-size: 0.82rem; max-width: 230px; }
</style>
<div class="page-header">
    <h2>Equipements</h2>
    <a class="btn" href="index.php?module=equipment&action=add">Ajouter</a>
</div>
<?php if (!empty($flash)) : ?>
    <p class="flash <?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>
<div class="toolbar">
    <a class="btn secondary" href="index.php?module=equipment&action=index&scope=public">Vue publique</a>
</div>
<section class="panel">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Numero de serie</th>
                <th>Statut</th>
                <th>AI Health</th>
                <th>Achat</th>
                <th>Cree le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $row) : ?>
            <tr>
                <td><?= htmlspecialchars((string) $row->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getCategory(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getSerialNumber(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <?php $health = $healthScores[(int) $row->getId()] ?? null; ?>
                    <?php if ($health !== null) : ?>
                        <?php $levelClass = strtolower($health['level']); ?>
                        <span class="health-badge <?= htmlspecialchars($levelClass, ENT_QUOTES, 'UTF-8') ?>">
                            <?= (int) $health['score'] ?>% <?= htmlspecialchars($health['level'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <span class="health-note"><?= htmlspecialchars($health['prediction'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php else : ?>
                        <span class="health-note">No score</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars((string) ($row->getPurchaseDate() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($row->getCreatedAt() ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <div class="table-actions">
                        <a href="index.php?module=equipment&action=edit&amp;id=<?= (int) $row->getId() ?>">Modifier</a>
                        <a href="index.php?module=equipment&action=delete&amp;id=<?= (int) $row->getId() ?>" onclick="return confirm('Supprimer cet equipement ?');">Supprimer</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($items === []) : ?>
            <tr><td colspan="9">Aucun equipement.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
