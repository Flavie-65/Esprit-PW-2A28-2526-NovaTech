<?php
include_once '../../Controller/CandidatureController.php';

$candidatureC = new CandidatureController();

// 🔥 ACTIONS (valider / refuser / supprimer)
if (isset($_GET['action']) && isset($_GET['id'])) {

    if ($_GET['action'] == 'valider') {
        $candidatureC->changerStatut($_GET['id'], 'validee');
    }

    if ($_GET['action'] == 'refuser') {
        $candidatureC->changerStatut($_GET['id'], 'refusee');
    }

    if ($_GET['action'] == 'supprimer') {
        $candidatureC->supprimerCandidature($_GET['id']);
    }

    header('Location: candidatures.php');
    exit();
}

// 🔥 FILTRE
$filtre = $_GET['statut'] ?? 'all';

// récupérer données
$liste = $candidatureC->afficherCandidatures();

// appliquer filtre
if ($filtre != 'all') {
    $liste = array_filter($liste, function($c) use ($filtre) {
        return $c['statut'] == $filtre;
    });
}

ob_start();
?>

<h2>Gestion des candidatures</h2>

<!-- 🔥 FILTRES -->
<div class="mb-3">
    <a href="candidatures.php" class="btn btn-secondary btn-sm">Toutes</a>
    <a href="candidatures.php?statut=en_attente" class="btn btn-warning btn-sm">En attente</a>
    <a href="candidatures.php?statut=validee" class="btn btn-success btn-sm">Validées</a>
    <a href="candidatures.php?statut=refusee" class="btn btn-danger btn-sm">Refusées</a>
</div>

<div class="card shadow">
    <div class="card-body">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Offre</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($liste as $c) { ?>
                <tr>
                    <td><?= $c['id']; ?></td>
                    <td><?= $c['nom']; ?></td>
                    <td><?= $c['email']; ?></td>

                    <!-- 🔥 STATUT -->
                    <td>
                        <?php
                        if ($c['statut'] == 'en_attente') {
                            echo '<span class="badge bg-warning">En attente</span>';
                        } elseif ($c['statut'] == 'validee') {
                            echo '<span class="badge bg-success">Validée</span>';
                        } else {
                            echo '<span class="badge bg-danger">Refusée</span>';
                        }
                        ?>
                    </td>

                    <!-- 🔥 TITRE OFFRE -->
                    <td><?= $c['titre']; ?></td>

                    <td><?= $c['date_candidature']; ?></td>

                    <!-- 🔥 ACTIONS -->
                    <td>

                        <a class="btn btn-sm btn-success"
                           href="candidatures.php?action=valider&id=<?= $c['id'] ?>">
                           ✔️
                        </a>

                        <a class="btn btn-sm btn-danger"
                           href="candidatures.php?action=refuser&id=<?= $c['id'] ?>">
                           ❌
                        </a>

                        <a class="btn btn-sm btn-dark"
                           href="candidatures.php?action=supprimer&id=<?= $c['id'] ?>"
                           onclick="return confirm('Supprimer cette candidature ?')">
                           🗑
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