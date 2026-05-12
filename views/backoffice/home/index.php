<?php
/** @var string $pageTitle */
/** @var string $message */
ob_start();
?>
<section class="panel">
    <div class="page-header">
        <h2><?= htmlspecialchars($pageTitle ?? 'Administration', ENT_QUOTES, 'UTF-8') ?></h2>
    </div>
    <?php if (($message ?? '') !== '') : ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <div class="toolbar">
        <a class="btn" href="index.php?module=home&action=dashboard">Ouvrir le dashboard</a>
        <a class="btn" href="index.php?module=equipment&action=index">Gerer les equipements</a>
        <a class="btn secondary" href="index.php?module=assignment&action=index">Gerer les affectations</a>
    </div>
</section>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
