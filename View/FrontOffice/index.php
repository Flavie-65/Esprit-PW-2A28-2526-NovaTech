<?php
include_once '../../Controller/OffreController.php';

$controller = new OffreController();
$offres = $controller->afficherOffres();

ob_start();
?>

<style>
.card {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
    height: 100%;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.badge-offre {
    background: rgba(29, 158, 117, 0.1);
    color: #1D9E75;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    margin-bottom: 10px;
}

.btn-postuler {
    background: linear-gradient(135deg, #1D9E75, #0F6E5E);
    border: none;
    border-radius: 10px;
    padding: 12px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-postuler:hover {
    transform: scale(1.02);
    background: #0c5c4b;
}
</style>

<!-- 🔹 TITRE -->
<div class="mb-4">
    <h2 class="fw-bold">💼 Offres d'emploi</h2>
    <p class="text-muted">Découvrez les meilleures opportunités et postulez en quelques clics</p>
</div>

<?php if (!empty($offres)): ?>

    <div class="row g-4">

    <?php foreach ($offres as $offre): ?>
        <div class="col-md-6">

            <div class="card shadow-sm p-4 h-100">

                <!-- BADGE -->
                <div class="badge-offre">Offre disponible</div>

                <!-- TITRE -->
                <h4 style="color:#0F6E5E;">
                    <?= $offre['titre'] ?>
                </h4>

                <!-- DESCRIPTION COURTE -->
                <p class="text-muted small">
                    <?= substr($offre['description'], 0, 100) ?>...
                </p>

                <!-- INFOS -->
                <div class="mt-2 text-muted">
                    💼 <b><?= $offre['competences'] ?></b><br>
                    💰 <?= $offre['budget'] ?> DT<br>
                    📅 <?= $offre['date_limite'] ?>
                </div>

                <!-- BOUTON -->
                <a href="#" class="btn btn-postuler mt-3">
                    🚀 Postuler
                </a>

            </div>

        </div>
    <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="alert alert-info">
        Aucune offre disponible pour le moment.
    </div>

<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>