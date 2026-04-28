<?php
require_once __DIR__ . "/../../Model/config.php";

if (!isset($_GET['id'])) {
    die("ID manquant");
}

$id = (int) $_GET['id'];
$pdo = config::getConnexion();

$sql = "SELECT c.*, o.titre 
        FROM candidatures c
        LEFT JOIN offres o ON c.offre_id = o.id
        WHERE c.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$c = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$c) {
    die("Candidature introuvable");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Détail Candidature</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #0f3d2e, #145c43);
    font-family: 'Segoe UI', sans-serif;
    color: #1f2937;
}
.container-box {
    background: #f9fafb;
    border-radius: 20px;
    padding: 30px;
    margin-top: 40px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}
.title {
    font-weight: 700;
    color: #145c43;
}
.btn-retour {
    border-radius: 12px;
    border: 2px solid #198754;
    color: #198754;
}
.btn-retour:hover {
    background: #198754;
    color: white;
}
.card-box {
    background: #ffffff;
    border-radius: 15px;
    padding: 18px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}
.label {
    font-size: 13px;
    color: #6b7280;
}
.value {
    font-size: 16px;
    font-weight: 600;
}
.badge-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
}
.en_attente { background: #fff3cd; color: #856404; }
.validee { background: #d1e7dd; color: #0f5132; }
.refusee { background: #f8d7da; color: #842029; }

.section-title {
    font-weight: 600;
    color: #145c43;
    margin-bottom: 15px;
}

.message-box {
    background: #eef2f7;
    border-radius: 10px;
    padding: 15px;
}

.file-box {
    display: none;
    margin-top: 15px;
}

.btn-file {
    background: #145c43;
    color: white;
    border-radius: 10px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}
@media(max-width: 900px) {
    .grid { grid-template-columns: 1fr; }
}
</style>

<script>
function toggleFile(id) {
    let el = document.getElementById(id);
    el.style.display = (el.style.display === "none") ? "block" : "none";
}
</script>

</head>

<body>

<div class="container">
<div class="container-box">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="title">📄 Détail de la candidature</h3>
        <small class="text-muted">Candidature #<?= $c['id'] ?></small>
    </div>
    <a href="candidatures.php" class="btn btn-retour">⬅ Retour</a>
</div>

<!-- 👤 PROFIL -->
<h5 class="section-title">👤 Profil candidat</h5>

<div class="grid mb-4">
    <div class="card-box">
        <div class="label">Nom</div>
        <div class="value"><?= htmlspecialchars($c['nom']) ?></div>
    </div>
    <div class="card-box">
        <div class="label">Email</div>
        <div class="value"><?= htmlspecialchars($c['email']) ?></div>
    </div>
    <div class="card-box">
        <div class="label">Téléphone</div>
        <div class="value"><?= $c['telephone'] ?></div>
    </div>
</div>

<hr>

<!-- 📌 INFOS -->
<h5 class="section-title">📌 Informations candidature</h5>

<div class="grid mb-4">
    <div class="card-box">
        <div class="label">Offre</div>
        <div class="value"><?= htmlspecialchars($c['titre']) ?></div>
    </div>
    <div class="card-box">
        <div class="label">Date</div>
        <div class="value"><?= $c['date_candidature'] ?></div>
    </div>
    <div class="card-box">
        <div class="label">Statut</div>
        <div class="value">
            <span class="badge-status <?= $c['statut'] ?>">
                <?= ucfirst(str_replace("_"," ",$c['statut'])) ?>
            </span>
        </div>
    </div>
</div>

<hr>

<!-- 🎓 PROFIL PRO -->
<h5 class="section-title">🎓 Profil professionnel</h5>

<div class="grid mb-4">
    <div class="card-box">
        <div class="label">Domaine</div>
        <div class="value"><?= $c['domaine'] ?></div>
    </div>
    <div class="card-box">
        <div class="label">Expérience</div>
        <div class="value"><?= $c['experience'] ?> ans</div>
    </div>
    <div class="card-box">
        <div class="label">Niveau</div>
        <div class="value"><?= $c['niveau'] ?></div>
    </div>
</div>

<hr>

<!-- 💬 MESSAGE (FIX ICI) -->
<?php if (!empty($c['message'])): ?>
<h5 class="section-title">💬 Message</h5>

<div class="message-box mb-4">
    <?= nl2br(htmlspecialchars($c['message'])) ?>
</div>

<hr>
<?php endif; ?>

<!-- 📄 CV -->
<h5 class="section-title">📄 CV</h5>

<button onclick="toggleFile('cv')" class="btn btn-file mb-2">
📄 Voir / Masquer CV
</button>

<div id="cv" class="file-box">
    <iframe src="../../uploads/<?= htmlspecialchars($c['cv']) ?>" width="100%" height="500px"></iframe>
</div>

<hr>

<!-- 📝 LETTRE -->
<h5 class="section-title">📝 Lettre de motivation</h5>

<button onclick="toggleFile('lettre')" class="btn btn-file mb-2">
📝 Voir / Masquer Lettre
</button>

<div id="lettre" class="file-box">
    <iframe src="../../uploads/<?= htmlspecialchars($c['lettre']) ?>" width="100%" height="500px"></iframe>
</div>

</div>
</div>

</body>
</html>