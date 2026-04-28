<?php
include_once __DIR__ . '/../../Controller/CandidatureController.php';

$candidatureC = new CandidatureController();

$message = "";
$type = "";

// 🔥 ACTIONS
if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'valider') {
        $candidatureC->changerStatut($id, 'validee');
        header('Location: candidatures.php');
        exit();
    }

    if ($action === 'refuser') {
        $candidatureC->changerStatut($id, 'refusee');
        header('Location: candidatures.php');
        exit();
    }

    if ($action === 'supprimer') {
        $candidatureC->supprimerCandidature($id);
        header('Location: candidatures.php');
        exit();
    }

    // 📅 PLANIFIER ENTRETIEN
    if ($action === 'planifier' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $date = $_POST['date'] ?? '';
        $heure = $_POST['heure'] ?? '';

        try {
            $candidatureC->planifierEntretien($id, $date, $heure);
            header("Location: candidatures.php?success=1");
            exit();
        } catch (Exception $e) {
            $message = $e->getMessage();
            $type = "danger";
        }
    }
}

// 🔍 DATA
$search = htmlspecialchars(trim($_GET['search'] ?? ''));
$filtre = $_GET['statut'] ?? 'all';

$liste = !empty($search)
    ? $candidatureC->rechercherCandidatures($search)
    : $candidatureC->afficherCandidatures();

if ($filtre !== 'all') {
    $liste = array_filter($liste, fn($c) => $c['statut'] === $filtre);
}

ob_start();
?>

<h2 class="mb-4">📄 Gestion des candidatures</h2>

<?php if (isset($_GET['success'])) { ?>
<div class="alert alert-success">✅ Entretien planifié avec succès</div>
<?php } ?>

<form method="GET" class="mb-3 d-flex" style="gap:10px;">
    <input type="text" name="search" class="form-control"
           placeholder="🔍 Rechercher..." value="<?= $search ?>">
    <button class="btn btn-success">Rechercher</button>
</form>

<div class="mb-3">
    <a href="candidatures.php" class="btn btn-outline-secondary btn-sm">Toutes</a>
    <a href="?statut=en_attente" class="btn btn-outline-warning btn-sm">En attente</a>
    <a href="?statut=validee" class="btn btn-outline-success btn-sm">Validées</a>
    <a href="?statut=entretien" class="btn btn-outline-primary btn-sm">Entretiens</a>
    <a href="?statut=refusee" class="btn btn-outline-danger btn-sm">Refusées</a>
</div>

<div class="card shadow-sm border-0">
<div class="card-body">

<table class="table table-hover align-middle">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Offre</th>
    <th>Statut</th>
    <th>Date</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php foreach ($liste as $c) { ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= htmlspecialchars($c['nom']) ?></td>
    <td><?= htmlspecialchars($c['email']) ?></td>
    <td><?= htmlspecialchars($c['titre']) ?></td>

    <!-- ✅ STATUT -->
    <td>
        <?php if ($c['statut'] === 'en_attente') { ?>
            <span class="badge bg-warning">⏳ En attente</span>

        <?php } elseif ($c['statut'] === 'validee') { ?>
            <span class="badge bg-success">✅ Validée</span>

        <?php } elseif ($c['statut'] === 'entretien') { ?>
            <span class="badge bg-primary">📅 Entretien</span>
            <div class="small text-primary mt-1">
                <?= $c['date_entretien'] ?> à <?= substr($c['heure_entretien'],0,5) ?>
            </div>

        <?php } else { ?>
            <span class="badge bg-danger">❌ Refusée</span>
        <?php } ?>
    </td>

    <td><?= $c['date_candidature'] ?></td>

    <!-- ✅ ACTIONS CORRIGÉES -->
    <td>
        <a href="show.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-info">👁</a>

        <!-- ✔ toujours visible -->
        <a href="?action=valider&id=<?= $c['id'] ?>" class="btn btn-sm btn-success">✔</a>

        <!-- ❌ toujours visible -->
        <a href="?action=refuser&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger">✖</a>

        <!-- 📅 planifier -->
        <?php if ($c['statut'] === 'validee' || $c['statut'] === 'entretien') { ?>
        <button type="button" class="btn btn-sm btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#planModal"
            data-id="<?= $c['id'] ?>"
            data-date="<?= $c['date_entretien'] ?? '' ?>"
            data-heure="<?= $c['heure_entretien'] ?? '' ?>">
            📅
        </button>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</tbody>
</table>

</div>
</div>

<!-- 📅 MODAL -->
<div class="modal fade" id="planModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" id="planForm">

<div class="modal-header">
<h5>📅 Planifier / Modifier entretien</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<label>Date :</label>
<input type="date" name="date" id="dateInput" class="form-control mb-2" required>

<label>Heure :</label>
<input type="time" name="heure" id="heureInput" class="form-control" required>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-success">Valider</button>
</div>

</form>

</div>
</div>
</div>

<script>
var planModal = document.getElementById('planModal');

planModal.addEventListener('show.bs.modal', function (event) {

    var button = event.relatedTarget;

    var id = button.getAttribute('data-id');
    var date = button.getAttribute('data-date');
    var heure = button.getAttribute('data-heure');

    document.getElementById('planForm').action =
        "candidatures.php?action=planifier&id=" + id;

    document.getElementById('dateInput').value = date ?? '';
    document.getElementById('heureInput').value = heure ?? '';
});
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>