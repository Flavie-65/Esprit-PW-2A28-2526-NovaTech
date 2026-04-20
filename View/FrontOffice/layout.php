<!DOCTYPE html>
<html>
<head>
    <title>OrgaSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- 🔹 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#0F6E56;">
    <div class="container">
        <a class="navbar-brand" href="index.php">OrgaSync</a>

        <div>
            <a href="index.php" class="text-white me-3">Accueil</a>
            <a href="offres.php" class="text-white">Offres</a>
        </div>
    </div>
</nav>

<!-- 🔹 CONTENU -->
<div class="container mt-4">
    <?php
    if (isset($content)) {
        echo $content;
    }
    ?>
</div>

</body>
</html>