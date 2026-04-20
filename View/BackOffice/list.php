<?php
include_once '../../Controller/OffreController.php';
$offreC = new OffreController();
$liste = $offreC->afficherOffres();

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gestion des offres</h2>

    <a href="add.php" class="btn" style="background:#1D9E75;color:white;">
        <i class="bi bi-plus-circle"></i> Ajouter
    </a>
</div>

<!-- 🔥 STATS (plus compactes) -->
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small class="text-muted">Total Offres</small>
            <h4><?= count($liste); ?></h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small class="text-muted">Candidatures</small>
            <h4>34</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small class="text-muted">En attente</small>
            <h4>8</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small class="text-muted">Validées</small>
            <h4>20</h4>
        </div>
    </div>

</div>

<!-- 🔥 TABLE PRO -->
<div class="card shadow-sm">

    <div class="card-body">

        <table class="table align-middle">

            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Compétences</th>
                    <th>Date limite</th>
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
                        <small class="text-muted"><?= substr($offre['description'], 0, 40); ?>...</small>
                    </td>

                    <td><?= $offre['competences']; ?></td>

                    <td>
                        <span class="badge bg-light text-dark">
                            <?= $offre['date_limite']; ?>
                        </span>
                    </td>

                    <td>
                        <strong><?= number_format($offre['budget'], 2); ?> DT</strong>
                    </td>

                    <td class="text-end">

                        <a class="btn btn-sm btn-outline-warning"
                           href="edit.php?id=<?= $offre['id']; ?>">
                           <i class="bi bi-pencil"></i>
                        </a>

                        <a class="btn btn-sm btn-outline-danger"
                           href="delete.php?id=<?= $offre['id']; ?>">
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