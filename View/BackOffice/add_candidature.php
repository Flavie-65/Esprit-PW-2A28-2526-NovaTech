<?php
include_once '../../Controller/CandidatureController.php';
include_once '../../Controller/OffreController.php';

$cC = new CandidatureController();
$oC = new OffreController();
$offres = $oC->afficherOffres();

if (isset($_POST['nom'])) {

    $cC->ajouterCandidature([
    'nom' => $_POST['nom'],
    'email' => $_POST['email'],
    'cv' => $_POST['cv'],
    'statut' => 'en_attente',
    'offre_id' => $_POST['offre_id']
]);

    header('Location: candidatures.php');
    exit();
}

ob_start();
?>

<h2>Ajouter une candidature</h2>

<div class="card shadow mt-4">
<div class="card-body">

<form method="POST">

<input type="text" name="nom" placeholder="Nom" class="form-control mb-2" required>

<input type="email" name="email" placeholder="Email" class="form-control mb-2" required>

<input type="text" name="cv" placeholder="Lien CV" class="form-control mb-2" required>

<select name="offre_id" class="form-control mb-2">
<?php foreach ($offres as $o) { ?>
    <option value="<?= $o['id'] ?>"><?= $o['titre'] ?></option>
<?php } ?>
</select>

<button class="btn" style="background-color:#1D9E75;color:white;">
    Ajouter
</button>

</form>

</div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>