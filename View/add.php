<?php
include_once '../Controller/OffreController.php';
include_once '../Model/Offre.php';

$controller = new OffreController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    if (!empty($titre) && !empty($description)) {
        $offre = new Offre(null, $titre, $description);
        $controller->addOffre($offre);

        header("Location: list.php?success=1");
exit();
    } else {
        $error = "Remplis tous les champs ❌";
    }
}
?>

<style>
body {
    background-color: #F1EFE8;
    font-family: Calibri;
}

h2 {
    color: #0F6E56;
}

.form-container {
    background: white;
    padding: 20px;
    margin: 50px auto;
    border-radius: 10px;
    width: 300px;
}

input, textarea {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #1D9E75;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    width: 100%;
}

.error {
    color: red;
    text-align: center;
}
</style>

<div class="form-container">
    <h2>Ajouter une offre</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="titre" placeholder="Titre" required>

        <textarea name="description" placeholder="Description" required></textarea>

        <button type="submit">Ajouter</button>
    </form>
</div>