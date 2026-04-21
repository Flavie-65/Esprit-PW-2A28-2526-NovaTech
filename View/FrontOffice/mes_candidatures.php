<?php
include_once '../../Model/config.php';

$db = config::getConnexion();

$email = $_POST['email'] ?? null;
$candidatures = [];

if ($email) {
    $sql = "SELECT c.*, o.titre 
            FROM candidatures c
            JOIN offres o ON c.offre_id = o.id
            WHERE c.email = :email
            ORDER BY c.date_candidature DESC";

    $query = $db->prepare($sql);
    $query->execute(['email' => $email]);
    $candidatures = $query->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mes candidatures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }

.card-main {
    border-radius:15px;
}

.btn-main {
    background:#0F6E56;
    color:white;
    border-radius:8px;
}
.btn-main:hover {
    background:#0c5c4b;
}

.card-candidature {
    border:none;
    border-radius:12px;
    transition:0.3s;
}
.card-candidature:hover {
    transform:translateY(-5px);
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

.badge-attente {
    background:#fff3cd;
    color:#856404;
    padding:5px 10px;
    border-radius:8px;
}

.badge-ok {
    background:#d4edda;
    color:#155724;
    padding:5px 10px;
    border-radius:8px;
}

.badge-no {
    background:#f8d7da;
    color:#721c24;
    padding:5px 10px;
    border-radius:8px;
}

.title {
    color:#0F6E56;
    font-weight:bold;
}
</style>

</head>

<body>

<div class="container mt-5">

<div class="card card-main p-4 shadow-sm">

<h3 class="text-center mb-4 title">📂 Mes candidatures</h3>

<!-- 🔍 FORMULAIRE PRO -->
<form method="POST">

    <input type="email"
           name="email"
           class="form-control mb-3"
           placeholder="Entrez votre email..."
           required>

    <button class="btn btn-main w-100">
        🔍 Voir mes candidatures
    </button>

</form>

<!-- ⬅️ RETOUR -->
<a href="index.php" class="btn btn-outline-dark mt-3">
    ⬅️ Retour aux offres
</a>

<hr>

<?php if ($email && empty($candidatures)): ?>
    <div class="alert alert-warning text-center mt-3">
        Aucune candidature trouvée pour cet email.
    </div>
<?php endif; ?>

<div class="row mt-3">

<?php foreach ($candidatures as $c): ?>

<div class="col-md-6 mb-3">

<div class="card card-candidature p-3 shadow-sm">

<h5 class="title"><?= $c['titre'] ?></h5>

<p class="text-muted"><?= $c['message'] ?></p>

<!-- 📅 DATE -->
<small class="text-muted">
📅 <?= $c['date_candidature'] ?>
</small>

<br><br>

<strong>Statut :</strong>

<?php if ($c['statut'] == 'en_attente'): ?>
    <span class="badge-attente">⏳ En attente</span>

<?php elseif ($c['statut'] == 'validee'): ?>
    <span class="badge-ok">✅ Acceptée</span>

<?php else: ?>
    <span class="badge-no">❌ Refusée</span>
<?php endif; ?>

<br><br>

<!-- 📄 TELECHARGER CV -->
<a href="../../uploads/<?= $c['cv'] ?>" 
   class="btn btn-sm btn-outline-success"
   target="_blank">
   📄 Télécharger CV
</a>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

</div>

</body>
</html>