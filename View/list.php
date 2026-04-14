<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
?>

<?php
include_once '../Controller/OffreController.php';

$controller = new OffreController();
$offres = $controller->listOffres();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des offres</title>

    <style>
    body {
        background-color: #F1EFE8;
        font-family: Calibri;
        margin: 0;
    }

    /* HEADER */
    .header {
        background-color: #0F6E56;
        color: white;
        padding: 20px;
        font-size: 22px;
        font-weight: bold;
    }

    /* CONTAINER */
    .container {
        width: 80%;
        margin: 30px auto;
    }

    h2 {
        color: #0F6E56;
    }

    /* CARD */
    .card {
        background: white;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .title {
        font-size: 18px;
        font-weight: bold;
        color: #2C2C2A;
    }

    .desc {
        margin-top: 5px;
        color: #555;
    }

    /* BUTTONS */
    .actions {
        margin-top: 10px;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        margin-right: 5px;
        font-size: 14px;
    }

    .btn-delete {
        background-color: #D85A30;
    }

    .btn-edit {
        background-color: #378ADD;
    }

    .btn-add {
        background-color: #1D9E75;
        float: right;
    }
    .btn:hover {
    opacity: 0.85;
}

.card:hover {
    transform: scale(1.01);
    transition: 0.2s;
}
.logout {
    float: right;
    color: white;
    text-decoration: none;
    font-size: 14px;
}

.logout:hover {
    text-decoration: underline;
}
    </style>
</head>

<body>

<div class="header">
    OrgaSync - Gestion des Offres

    <a href="../logout.php" class="logout">Déconnexion</a>
</div>

<div class="container">
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <p style="color: green; text-align:center; font-weight:bold;">
        Offre supprimée avec succès ✅
    </p>
<?php endif; ?>

    <h2>
        Liste des offres
        <a href="add.php" class="btn btn-add">+ Ajouter</a>
    </h2>

    <?php foreach ($offres as $offre): ?>
        <div class="card">

            <div class="title"><?= $offre['titre'] ?></div>

            <div class="desc"><?= $offre['description'] ?></div>

            <div class="actions">
                
                <a href="/recrutement/delete.php?id=<?= $offre['id'] ?>" 
   class="btn btn-delete"
   onclick="return confirm('Supprimer cette offre ?')">
    Supprimer
</a>

                <a href="/recrutement/edit.php?id=<?= $offre['id'] ?>" class="btn btn-edit">
                    Modifier
                </a>
               
                
            </div>

        </div>
    <?php endforeach; ?>

</div>

</body>
</html>