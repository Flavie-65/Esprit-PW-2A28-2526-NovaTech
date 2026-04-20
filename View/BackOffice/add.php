<?php
include_once '../../Controller/OffreController.php';

$offreC = new OffreController();

// 🔥 TRAITEMENT FORMULAIRE
if (isset($_POST['titre'])) {

    $offreC->ajouterOffre(
        $_POST['titre'],
        $_POST['description'],
        $_POST['competences'],
        $_POST['date_limite'],
        $_POST['budget']
    );

    // 🔁 redirection vers liste
    header('Location: list.php');
    exit();
}

ob_start();
?>

<h2>Ajouter une offre</h2>

<div class="card shadow mt-4">
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label>Titre</label>
                <input type="text" name="titre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label>Compétences</label>
                <input type="text" name="competences" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Date limite</label>
                <input type="date" name="date_limite" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Budget</label>
                <input type="number" name="budget" class="form-control" required>
            </div>

            <button type="submit" class="btn" style="background-color:#1D9E75;color:white;">
                💾 Enregistrer
            </button>

            <a href="list.php" class="btn btn-secondary">Annuler</a>

        </form>

    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>