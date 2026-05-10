<?php
include_once '../../Controller/CandidatureController.php';
require_once '../../Model/Candidature.php';

$cC = new CandidatureController();
$liste = $cC->afficherCandidatures();

ob_start();
?>

<h2 class="mb-4">📋 Gestion des candidatures</h2>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Offre</th>
    <th>Statut</th>
    <th>Score Profil</th>
</tr>
</thead>

<tbody>

<?php foreach ($liste as $c): ?>

<tr>

    <td><?= $c['id'] ?></td>

    <td><?= htmlspecialchars($c['nom']) ?></td>

    <td><?= htmlspecialchars($c['email']) ?></td>

    <td><?= htmlspecialchars($c['titre']) ?></td>

    <!-- 🔹 STATUT -->
    <td>
        <?php
        switch($c['statut']) {
            case 'validee':
                echo "<span class='badge bg-success'>Validée</span>";
                break;
            case 'refusee':
                echo "<span class='badge bg-danger'>Refusée</span>";
                break;
            case 'entretien':
                echo "<span class='badge bg-info'>Entretien</span>";
                break;
            default:
                echo "<span class='badge bg-warning text-dark'>En attente</span>";
        }
        ?>
    </td>

    <!-- 🔥 SCORE PROFIL PRO -->
    <td>
        <?php
        // 🔥 calcul score
        $score = Candidature::calculerScore($c);

        // 🔥 niveau (faible / moyen / fort)
        $niveau = Candidature::getNiveauProfil($score);
        ?>

        <span class="badge bg-<?= $niveau['class'] ?>">
            <?= $niveau['label'] ?> (<?= $score ?>%)
        </span>
    </td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>