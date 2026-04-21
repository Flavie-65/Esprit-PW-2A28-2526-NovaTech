<?php
include_once '../../Controller/OffreController.php';

$controller = new OffreController();
$offres = $controller->afficherOffres();

ob_start();
?>

<h2 class="mb-4">💼 Nos Offres d'emploi</h2>

<?php if (!empty($offres)): ?>

<div class="row">
<?php foreach ($offres as $offre): ?>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-4">

            <div class="badge bg-success mb-2">Offre disponible</div>

            <h4 style="color:#0F6E5E;">
                <?= $offre['titre'] ?>
            </h4>

            <p><?= substr($offre['description'], 0, 100) ?>...</p>

            <div class="text-muted">
                💼 <b><?= $offre['competences'] ?></b><br>
                💰 <?= $offre['budget'] ?> DT<br>
                📅 <?= $offre['date_limite'] ?>
            </div>

            <a href="postuler.php?id=<?= $offre['id'] ?>" class="btn btn-success mt-3">
                🚀 Postuler
            </a>

        </div>
    </div>
<?php endforeach; ?>
</div>

<?php else: ?>
    <div class="alert alert-info">Aucune offre disponible.</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>