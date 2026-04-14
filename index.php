<?php
include_once 'Controller/OffreController.php';
include_once 'config.php';

$controller = new OffreController();
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];

    $db = config::getConnexion();
    $sql = "SELECT * FROM offres WHERE titre LIKE ?";
    $query = $db->prepare($sql);
    $query->execute(["%" . $search . "%"]);
    $offres = $query->fetchAll();
} else {
    $offres = $controller->listOffres();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Offres disponibles</title>

    <style>
    body {
        font-family: Calibri;
        background-color: #F1EFE8;
        margin: 0;
    }

    .header {
    background-color: #0F6E56;
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 22px;
    font-weight: bold;
}

.admin-btn {
    color: white;
    text-decoration: none;
    background: #1D9E75;
    padding: 8px 15px;
    border-radius: 5px;
}

    .header {
        background-color: #0F6E56;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 24px;
    }

    .container {
        width: 80%;
        margin: 30px auto;
    }

    .card {
    background: white;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

    .title {
        font-size: 18px;
        font-weight: bold;
    }

    .desc {
        margin-top: 5px;
        color: #555;
    }
    </style>
</head>

<body>

<div class="header">
    <div class="logo">OrgaSync</div>

    <a href="login.php" class="admin-btn">Admin</a>
</div>

<div class="container">
    <h2 style="text-align:center; color:#0F6E56;">
    Découvrez nos offres disponibles
</h2>
<form method="GET" style="text-align:center; margin-bottom:20px;">
    <input type="text" name="search" placeholder="Rechercher une offre..." 
           style="padding:10px; width:300px; border-radius:5px; border:1px solid #ccc;">

    <button type="submit" 
            style="padding:10px 15px; background:#1D9E75; color:white; border:none; border-radius:5px;">
        🔍 Rechercher
    </button>
</form>

<?php foreach ($offres as $offre): ?>
    <div class="card">
        <div class="title"><?= $offre['titre'] ?></div>
        <div class="desc"><?= $offre['description'] ?></div>
    </div>
<?php endforeach; ?>

</div>

</body>
</html>