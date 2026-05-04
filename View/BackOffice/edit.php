<?php
include_once '../../Controller/OffreController.php';

$offreC = new OffreController();

// 🔹 récupérer l'offre
if (isset($_GET['id'])) {
    $offre = $offreC->recupererOffre($_GET['id']);

    if (!$offre) {
        die("❌ Offre introuvable");
    }
} else {
    die("❌ ID manquant");
}

// 🔹 modifier
if (isset($_POST['titre'])) {

    $result = $offreC->modifierOffre($_POST['id'], [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'competences' => $_POST['competences'],
        'date_limite' => $_POST['date_limite'],
        'budget' => $_POST['budget']
    ]);

    if ($result) {
        header('Location: list.php');
        exit();
    } else {
        $error = "❌ Erreur lors de la modification";
    }
}

ob_start();
?>

<h2>Modifier une offre</h2>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">

    <!-- 🔥 ID caché -->
    <input type="hidden" name="id" value="<?= $offre['id']; ?>">

    <input type="text" name="titre"
        value="<?= htmlspecialchars($offre['titre']); ?>"
        class="form-control mb-2" required>

    <textarea name="description"
        class="form-control mb-2"
        required><?= htmlspecialchars($offre['description']); ?></textarea>

    <input type="text" name="competences"
        value="<?= htmlspecialchars($offre['competences']); ?>"
        class="form-control mb-2" required>

    <input type="date" name="date_limite"
        value="<?= $offre['date_limite']; ?>"
        class="form-control mb-2" required>

    <input type="number" name="budget"
        value="<?= $offre['budget']; ?>"
        class="form-control mb-2" required>

    <button type="submit" class="btn btn-success">Modifier</button>

</form>

<?php
$content = ob_get_clean();
include 'layout.php';
?>