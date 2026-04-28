<?php
include_once '../../Controller/OffreController.php';
include_once '../../Controller/CandidatureController.php';

$offreC = new OffreController();
$cC = new CandidatureController();

$liste = $offreC->afficherOffres();

$stats = $cC->getStats();
$total = $stats['total'];
$attente = $cC->countEnAttente();
$validees = $cC->countValidees();
$refusees = $total - ($attente + $validees);

ob_start();
?>

<style>
.card-stat {
    border-radius: 15px;
    padding: 20px;
    color: white;
    transition: 0.3s;
}
.card-stat:hover {
    transform: translateY(-5px);
}

.bg-main { background: linear-gradient(135deg, #1D9E75, #0F6E56); }
.bg-blue { background: linear-gradient(135deg, #378ADD, #1F5DA8); }
.bg-orange { background: linear-gradient(135deg, #F39C12, #D68910); }
.bg-green { background: linear-gradient(135deg, #2ECC71, #27AE60); }
.bg-red { background: linear-gradient(135deg, #E74C3C, #C0392B); }

.table thead {
    background: #f8f9fa;
}

.table tbody tr {
    transition: 0.2s;
}
.table tbody tr:hover {
    background: #f1f1f1;
}

.badge-date {
    background: #eee;
    padding: 6px 10px;
    border-radius: 8px;
}

.btn-action {
    border-radius: 8px;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📊 Gestion des offres</h2>

    <a href="add.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Ajouter
    </a>
</div>

<!-- 🔥 STATS PRO -->
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card-stat bg-main">
            <h6>Total Offres</h6>
            <h2><?= count($liste); ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat bg-blue">
            <h6>Candidatures</h6>
            <h2><?= $total; ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat bg-orange">
            <h6>En attente</h6>
            <h2><?= $attente; ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat bg-green">
            <h6>Validées</h6>
            <h2><?= $validees; ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat bg-red">
            <h6>Refusées</h6>
            <h2><?= $refusees; ?></h2>
        </div>
    </div>

</div>

<!-- 🔥 TABLE PRO -->
<div class="card shadow-sm">
    <div class="card-body">

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Offre</th>
                    <th>Compétences</th>
                    <th>Date</th>
                    <th>Budget</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($liste as $offre) { ?>
                <tr>

                    <td><?= $offre['id']; ?></td>

                    <td>
                        <strong><?= $offre['titre']; ?></strong><br>
                        <small class="text-muted">
                            <?= substr($offre['description'], 0, 50); ?>...
                        </small>
                    </td>

                    <td>
                        <span class="badge bg-light text-dark">
                            <?= $offre['competences']; ?>
                        </span>
                    </td>

                    <td>
                        <span class="badge-date">
                            <?= $offre['date_limite']; ?>
                        </span>
                    </td>

                    <td>
                        <strong><?= number_format($offre['budget'], 2); ?> DT</strong>
                    </td>

                    <td class="text-end">

                        <a class="btn btn-sm btn-warning btn-action"
                           href="edit.php?id=<?= $offre['id']; ?>">
                           <i class="bi bi-pencil"></i>
                        </a>

                        <a class="btn btn-sm btn-danger btn-action"
                           href="delete.php?id=<?= $offre['id']; ?>"
                           onclick="return confirm('Supprimer cette offre ?')">
                           <i class="bi bi-trash"></i>
                        </a>

                    </td>

                </tr>
            <?php } ?>
            </tbody>

        </table>

    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>