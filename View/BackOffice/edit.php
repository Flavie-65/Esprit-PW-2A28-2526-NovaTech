<?php
include_once '../../Controller/OffreController.php';

$offreC = new OffreController();

// 🔹 récupérer l'offre
if (isset($_GET['id'])) {
    $offre = $offreC->recupererOffre($_GET['id']);
}

// 🔹 modifier
if (isset($_POST['titre'])) {

    $offreC->modifierOffre(
        $_POST['titre'],
        $_POST['description'],
        $_POST['competences'],
        $_POST['date_limite'],
        $_POST['budget'],
        $_GET['id']
    );

    header('Location: list.php');
    exit();
}

ob_start();
?>

<h2>Modifier une offre</h2>

<form method="POST">

<input type="text" name="titre" value="<?= $offre['titre']; ?>" class="form-control mb-2" required>

<textarea name="description" class="form-control mb-2" required><?= $offre['description']; ?></textarea>

<input type="text" name="competences" value="<?= $offre['competences']; ?>" class="form-control mb-2" required>

<input type="date" name="date_limite" value="<?= $offre['date_limite']; ?>" class="form-control mb-2" required>

<input type="number" name="budget" value="<?= $offre['budget']; ?>" class="form-control mb-2" required>

<button class="btn btn-success">Modifier</button>

</form>

<?php
$content = ob_get_clean();
include 'layout.php';
?>