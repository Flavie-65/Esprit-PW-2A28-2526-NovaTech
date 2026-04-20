<?php
include_once '../../Controller/CandidatureController.php';
include_once '../../Controller/OffreController.php';

$cC = new CandidatureController();
$oC = new OffreController();

// 🔹 Données dynamiques
$total = $cC->countCandidatures();
$attente = $cC->countEnAttente();
$validees = $cC->countValidees();

$liste = $oC->afficherOffres(); // pour compter les offres

ob_start();
?>

<h2 class="mb-4">Tableau de bord</h2>

<a href="add.php" class="btn mb-4" style="background:#1D9E75;color:white;">
    + Ajouter une offre
</a>

<div class="row g-4">

    <!-- 🔹 Total Offres -->
    <div class="col-md-3">
        <div class="card text-white shadow-sm" style="background:#1D9E75;">
            <div class="card-body">
                <h6>Total Offres</h6>
                <h2><?= count($liste); ?></h2>
            </div>
        </div>
    </div>

    <!-- 🔹 Candidatures -->
    <div class="col-md-3">
        <div class="card text-white shadow-sm" style="background:#378ADD;">
            <div class="card-body">
                <h6>Candidatures</h6>
                <h2><?= $total; ?></h2>
            </div>
        </div>
    </div>

    <!-- 🔹 En attente -->
    <div class="col-md-3">
        <div class="card text-white shadow-sm" style="background:#D85A30;">
            <div class="card-body">
                <h6>En attente</h6>
                <h2><?= $attente; ?></h2>
            </div>
        </div>
    </div>

    <!-- 🔹 Validées -->
    <div class="col-md-3">
        <div class="card text-white shadow-sm" style="background:#7F77DD;">
            <div class="card-body">
                <h6>Validées</h6>
                <h2><?= $validees; ?></h2>
            </div>
        </div>
    </div>

</div>


<!-- 🔥 Activité -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        Activité récente
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>🟢 Nouvelle offre ajoutée</li>
            <li>🔵 Candidature reçue</li>
            <li>🟡 Candidature validée</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>