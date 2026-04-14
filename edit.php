<?php
include_once 'Controller/OffreController.php';
include_once 'Model/Offre.php';

$controller = new OffreController();

$id = $_GET['id'];
$offre = $controller->getOffre($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    $updatedOffre = new Offre($id, $titre, $description);
    $controller->updateOffre($updatedOffre);

   header("Location: View/list.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier offre</title>

    <style>
    body {
        background-color: #F1EFE8;
        font-family: Calibri;
        margin: 0;
    }

    .header {
        background-color: #0F6E56;
        color: white;
        padding: 20px;
        font-size: 22px;
        font-weight: bold;
    }

    .container {
        width: 400px;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    h2 {
        color: #0F6E56;
        text-align: center;
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button {
        width: 100%;
        background-color: #1D9E75;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .back {
        display: block;
        margin-top: 10px;
        text-align: center;
        text-decoration: none;
        color: #378ADD;
    }
    </style>
</head>

<body>

<div class="header">
    OrgaSync - Modifier une offre
</div>

<div class="container">
    <h2>Modifier une offre</h2>

    <form method="POST">
        <input type="text" name="titre" value="<?= $offre['titre'] ?>" required>

        <textarea name="description" required><?= $offre['description'] ?></textarea>

        <button type="submit">Mettre à jour</button>
    </form>

    <a href="list.php" class="back">← Retour</a>
</div>

</body>
</html>