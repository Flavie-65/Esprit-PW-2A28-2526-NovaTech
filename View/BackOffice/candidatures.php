<?php
// 🔹 Controller + Model
include_once __DIR__ . '/../../Controller/CandidatureController.php';
require_once __DIR__ . '/../../Model/Candidature.php';

// 🔹 Instance controller
$cC = new CandidatureController();


/* ============================================================
   🔥 1. GESTION DES ACTIONS (valider, refuser, supprimer...)
   ============================================================ */

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = (int) $_GET['id'];         // ID candidature
    $action = $_GET['action'];       // action demandée

    // ✅ VALIDER candidature
    if ($action === 'valider') {
        $cC->changerStatut($id, 'validee');
        header('Location: candidatures.php');
        exit();
    }

    // ❌ REFUSER candidature
    if ($action === 'refuser') {
        $cC->changerStatut($id, 'refusee');
        header('Location: candidatures.php');
        exit();
    }

    // 🗑 SUPPRIMER candidature
    if ($action === 'supprimer') {

        $result = $cC->supprimerCandidature($id);

        // 🔁 REDIRECTION AVEC MESSAGE
        if ($result) {
            header('Location: candidatures.php?success=delete');
        } else {
            header('Location: candidatures.php?error=delete');
        }
        exit();
    }

    // 📅 PLANIFIER entretien
    if ($action === 'planifier' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $date = $_POST['date'] ?? '';
        $heure = substr($_POST['heure'] ?? '', 0, 5);

        $cC->planifierEntretien($id, $date, $heure);

        header("Location: candidatures.php?success=plan");
        exit();
    }
}


/* ============================================================
   🔥 2. RÉCUPÉRATION DES DONNÉES (liste + recherche + filtre)
   ============================================================ */

$search = trim($_GET['search'] ?? '');     // valeur recherche
$filtre = $_GET['statut'] ?? 'all';        // filtre statut

// 🔍 SI recherche → search()
// 📄 SINON → afficher tout
$liste = !empty($search)
    ? $cC->rechercherCandidatures($search)
    : $cC->afficherCandidatures();

// 🔹 FILTRAGE PAR STATUT
if ($filtre !== 'all') {
    $liste = array_filter($liste, fn($c) => $c['statut'] === $filtre);
}


// 🔹 BUFFER HTML
ob_start();
?>

<h2 class="mb-4">📄 Gestion des candidatures</h2>


<!-- ============================================================
     🔥 3. MESSAGES (SUCCESS / ERROR)
     ============================================================ -->

<?php if (isset($_GET['success'])): ?>

    <?php if ($_GET['success'] == 'delete'): ?>
        <div class="alert alert-success">
            ✅ Candidature supprimée avec succès
        </div>

    <?php elseif ($_GET['success'] == 'plan'): ?>
        <div class="alert alert-success">
            📅 Entretien planifié avec succès
        </div>

    <?php endif; ?>

<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger">
    ❌ Une erreur est survenue
</div>
<?php endif; ?>


<!-- ============================================================
     🔍 4. FORMULAIRE DE RECHERCHE
     ============================================================ -->

<form method="GET" class="mb-3 d-flex gap-2">
    <input type="text" name="search" class="form-control"
           placeholder="🔍 Rechercher..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-success">Rechercher</button>
</form>


<!-- ============================================================
     🔹 5. FILTRES PAR STATUT
     ============================================================ -->

<div class="mb-3">
    <a href="candidatures.php" class="btn btn-outline-secondary btn-sm">Toutes</a>
    <a href="?statut=en_attente" class="btn btn-outline-warning btn-sm">En attente</a>
    <a href="?statut=validee" class="btn btn-outline-success btn-sm">Validées</a>
    <a href="?statut=entretien" class="btn btn-outline-primary btn-sm">Entretiens</a>
    <a href="?statut=refusee" class="btn btn-outline-danger btn-sm">Refusées</a>
</div>


<!-- ============================================================
     📊 6. TABLE DES CANDIDATURES
     ============================================================ -->

<div class="card shadow-sm border-0">
<div class="card-body">

<table class="table table-hover align-middle text-center">

<thead class="table-light">
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Offre</th>
    <th>Statut</th>
    <th>Score</th>
    <th>Priorité</th>
    <th>Date</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php foreach ($liste as $c): ?>

<?php
// 🔥 CALCUL SCORE + PRIORITÉ
$score = Candidature::calculerScore($c);
$niveau = Candidature::getNiveauProfil($score);
$priorite = Candidature::getPriorite($score);
?>

<tr>

<td><?= $c['id'] ?></td>
<td><?= htmlspecialchars($c['nom']) ?></td>
<td><?= htmlspecialchars($c['email']) ?></td>
<td><?= htmlspecialchars($c['titre']) ?></td>


<!-- ============================================================
     🔹 STATUT VISUEL
     ============================================================ -->
<td>
<?php if ($c['statut'] === 'en_attente'): ?>
    <span class="badge bg-warning text-dark">⏳ En attente</span>

<?php elseif ($c['statut'] === 'validee'): ?>
    <span class="badge bg-success">✅ Validée</span>

<?php elseif ($c['statut'] === 'entretien'): ?>
    <span class="badge bg-primary d-block mb-1">📅 Entretien</span>
    <small class="text-muted">
        <?= date('d/m/Y', strtotime($c['date_entretien'])) ?>
        à <?= substr($c['heure_entretien'],0,5) ?>
    </small>

<?php else: ?>
    <span class="badge bg-danger">❌ Refusée</span>
<?php endif; ?>
</td>


<!-- 🔥 SCORE -->
<td>
    <span class="badge bg-<?= $niveau['class'] ?>">
        <?= $niveau['label'] ?> (<?= $score ?>%)
    </span>
</td>

<!-- 🔥 PRIORITÉ -->
<td>
    <span class="badge bg-<?= $priorite['class'] ?>">
        <?= $priorite['label'] ?>
    </span>
</td>

<td><?= date('d/m/Y H:i', strtotime($c['date_candidature'])) ?></td>


<!-- ============================================================
     🔥 7. ACTIONS (buttons)
     ============================================================ -->

<td>
<div class="d-flex justify-content-center gap-2">

    <!-- 👁 VOIR -->
    <a href="show.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-info">👁</a>

    <!-- ✔ VALIDER -->
    <a href="?action=valider&id=<?= $c['id'] ?>" class="btn btn-sm btn-success">✔</a>

    <!-- MENU ACTIONS -->
    <div class="dropdown">
        <button class="btn btn-sm btn-dark dropdown-toggle"
                data-bs-toggle="dropdown">⋮</button>

        <ul class="dropdown-menu">

            <!-- ❌ REFUSER -->
            <li>
                <a class="dropdown-item text-danger"
                   href="?action=refuser&id=<?= $c['id'] ?>">
                   ❌ Refuser
                </a>
            </li>

            <!-- 🗑 SUPPRIMER -->
            <li>
                <a class="dropdown-item text-dark"
                   href="?action=supprimer&id=<?= $c['id'] ?>"
                   onclick="return confirm('Confirmer suppression ?')">
                   🗑 Supprimer
                </a>
            </li>

            <!-- 📅 PLANIFIER -->
            <?php if ($c['statut'] === 'validee' || $c['statut'] === 'entretien'): ?>
            <li>
                <button class="dropdown-item text-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#planModal"
                        data-id="<?= $c['id'] ?>"
                        data-date="<?= $c['date_entretien'] ?? '' ?>"
                        data-heure="<?= $c['heure_entretien'] ?? '' ?>">
                    📅 Planifier
                </button>
            </li>
            <?php endif; ?>

        </ul>
    </div>

</div>
</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>
</div>


<!-- ============================================================
     📅 8. MODAL PLANIFICATION ENTRETIEN
     ============================================================ -->

<div class="modal fade" id="planModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" id="planForm">

<div class="modal-header">
<h5>📅 Planifier entretien</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<input type="date" name="date" id="dateInput" class="form-control mb-2" required>
<input type="time" name="heure" id="heureInput" class="form-control" required>
</div>

<div class="modal-footer">
<button type="submit" class="btn btn-success">Valider</button>
</div>

</form>

</div>
</div>
</div>


<!-- ============================================================
     ⚙️ 9. SCRIPT JS POUR MODAL
     ============================================================ -->

<script>
var planModal = document.getElementById('planModal');

planModal.addEventListener('show.bs.modal', function (event) {

    var button = event.relatedTarget;

    // 🔹 Injecter ID dans le form
    document.getElementById('planForm').action =
        "candidatures.php?action=planifier&id=" + button.getAttribute('data-id');

    // 🔹 Pré-remplir
    document.getElementById('dateInput').value = button.getAttribute('data-date') || '';
    document.getElementById('heureInput').value = button.getAttribute('data-heure') || '';
});
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>