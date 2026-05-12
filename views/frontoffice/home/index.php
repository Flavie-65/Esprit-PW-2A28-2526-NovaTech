<?php
/** @var string $pageTitle */
/** @var string $message */
ob_start();
?>
<section class="hero">
    <h1><?= htmlspecialchars($pageTitle ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
    <?php if (($message ?? '') !== '') : ?>
        <p class="muted"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <div class="toolbar">
        <a class="btn" href="index.php?module=equipment&action=index&scope=public">Equipements</a>
        <a class="btn" href="index.php?module=assignment&action=index&scope=public">Affectations</a>
    </div>
</section>
<?php
$contenu = ob_get_clean();
require __DIR__ . '/../template.php';
