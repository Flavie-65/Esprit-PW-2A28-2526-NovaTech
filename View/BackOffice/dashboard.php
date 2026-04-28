<?php
include_once '../../Controller/CandidatureController.php';
include_once '../../Controller/OffreController.php';

$cC = new CandidatureController();
$oC = new OffreController();

// 🔥 STATS OPTIMISÉES (1 seule requête)
$stats = $cC->getStats();

$total     = $stats['total'];
$validees  = $stats['valide'];
$refusees  = $stats['refuse'];
$attente   = $stats['attente'];

$liste = $oC->afficherOffres();

ob_start();
?>

<h2 class="mb-4 fw-bold">📊 Tableau de bord</h2>

<a href="add.php" class="btn mb-4 px-4 py-2" style="background:#14532d;color:white;border-radius:10px;">
    + Ajouter une offre
</a>

<div class="row g-4">

    <!-- Total Offres -->
    <div class="col-md-3">
        <div class="card shadow border-0" style="background:linear-gradient(135deg,#14532d,#1D9E75);color:white;border-radius:15px;">
            <div class="card-body">
                <h6>Total Offres</h6>
                <h2><?= count($liste); ?></h2>
            </div>
        </div>
    </div>

    <!-- Candidatures -->
    <div class="col-md-3">
        <div class="card shadow border-0" style="background:linear-gradient(135deg,#1e3a8a,#3b82f6);color:white;border-radius:15px;">
            <div class="card-body">
                <h6>Candidatures</h6>
                <h2><?= $total ?></h2>
            </div>
        </div>
    </div>

    <!-- En attente -->
    <div class="col-md-3">
        <div class="card shadow border-0" style="background:linear-gradient(135deg,#b45309,#f59e0b);color:white;border-radius:15px;">
            <div class="card-body">
                <h6>En attente (%)</h6>
                <h2><?= $attente ?>%</h2>
            </div>
        </div>
    </div>

    <!-- Validées -->
    <div class="col-md-3">
        <div class="card shadow border-0" style="background:linear-gradient(135deg,#065f46,#10b981);color:white;border-radius:15px;">
            <div class="card-body">
                <h6>Validées (%)</h6>
                <h2><?= $validees ?>%</h2>
            </div>
        </div>
    </div>

    <!-- Refusées -->
    <div class="col-md-3">
        <div class="card shadow border-0" style="background:linear-gradient(135deg,#7f1d1d,#ef4444);color:white;border-radius:15px;">
            <div class="card-body">
                <h6>Refusées (%)</h6>
                <h2><?= $refusees ?>%</h2>
            </div>
        </div>
    </div>

</div>

<!-- 🔥 BARRE VISUELLE (PRO MAX) -->
<div class="card mt-5 shadow border-0">
    <div class="card-body">

        <h5 class="mb-4">📈 Répartition des candidatures</h5>

        <div class="progress" style="height:25px;border-radius:15px;">
            <div class="progress-bar bg-success" style="width: <?= $validees ?>%">
                <?= $validees ?>%
            </div>

            <div class="progress-bar bg-warning text-dark" style="width: <?= $attente ?>%">
                <?= $attente ?>%
            </div>

            <div class="progress-bar bg-danger" style="width: <?= $refusees ?>%">
                <?= $refusees ?>%
            </div>
        </div>

    </div>
</div>

<!-- Activité -->
<div class="card shadow-sm mt-4 border-0">
    <div class="card-header bg-white fw-bold">
        ⚡ Activité récente
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>🟢 Nouvelle offre ajoutée</li>
            <li>🔵 Candidature reçue</li>
            <li>🟡 Candidature validée</li>
            <li>🔴 Candidature refusée</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>