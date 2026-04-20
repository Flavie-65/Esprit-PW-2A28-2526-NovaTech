<?php
session_start();

// 🔒 Protection (empêche accès sans login)
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OrgaSync</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #F4F6F9;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #0F6E56;
            color: white;
            padding: 20px;
        }

        .sidebar h6 {
            margin-top: 20px;
            font-size: 13px;
            text-transform: uppercase;
            opacity: 0.7;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #1D9E75;
        }

        .topbar {
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .shadow-sm {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>

<div class="d-flex">

    <!-- 🔹 SIDEBAR -->
    <div class="sidebar">

        <h4 class="mb-4">OrgaSync</h4>

        <a href="dashboard.php">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <h6>Utilisateurs</h6>
        <a href="#">
            <i class="bi bi-person"></i> Liste utilisateurs
        </a>

        <h6>Recrutement</h6>
        <a href="list.php">
            <i class="bi bi-briefcase"></i> Offres d’emploi
        </a>
        <a href="candidatures.php">
            <i class="bi bi-file-earmark-text"></i> Candidatures
        </a>

        <h6>Projets</h6>
        <a href="#">
            <i class="bi bi-folder"></i> Projets
        </a>
        <a href="#">
            <i class="bi bi-list-check"></i> Tâches
        </a>

        <h6>Collaboration</h6>
        <a href="#">
            <i class="bi bi-calendar"></i> Planning
        </a>
        <a href="#">
            <i class="bi bi-people"></i> Équipe
        </a>

        <h6>Paramètres</h6>
        <a href="#">
            <i class="bi bi-gear"></i> Paramètres
        </a>

    </div>

    <!-- 🔹 CONTENU -->
    <div class="flex-grow-1">

        <!-- 🔥 TOPBAR AVEC LOGOUT -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Bienvenue, <?= $_SESSION['admin']; ?> 👋
            </h5>

            <a href="logout.php" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </div>

        <!-- CONTENU PAGE -->
        <div class="p-4">
            <?php
            if (isset($content)) {
                echo $content;
            }
            ?>
        </div>

    </div>

</div>

</body>
</html>