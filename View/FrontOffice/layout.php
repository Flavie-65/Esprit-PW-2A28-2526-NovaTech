<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OrgaSync</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #F4F6F9;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 20px;
        }

        .nav-link-custom {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-link-custom:hover {
            text-decoration: underline;
            color: #D1F2EB;
        }

        /* 🔥 bouton actif */
        .nav-active {
            text-decoration: underline;
            color: #D1F2EB !important;
            font-weight: bold;
        }

        .container {
            margin-top: 30px;
        }
    </style>
</head>

<body>

<!-- 🔹 NAVBAR PRO -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#0F6E56;">
    <div class="container">

        <!-- Logo -->
       <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="http://localhost:8080/jobboard/assets/images/logo.png" width="40">
    <span class="ms-2">OrgaSync</span>
</a>

        <!-- Menu -->
        <div>
            <a href="index.php" class="nav-link-custom">
                <i class="bi bi-house"></i> Accueil
            </a>

            <a href="mes_candidatures.php" class="nav-link-custom">
                <i class="bi bi-folder"></i> Mes candidatures
            </a>

            <a href="offres.php" class="nav-link-custom">
                <i class="bi bi-briefcase"></i> Offres
            </a>
        </div>

    </div>
</nav>

<!-- 🔹 CONTENU -->
<div class="container">
    <?php
    if (isset($content)) {
        echo $content;
    }
    ?>
</div>

</body>
</html>