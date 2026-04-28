<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../Model/config.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Offre invalide");

$db = config::getConnexion();

// 🔥 récupérer offre + date
$stmt = $db->prepare("SELECT titre, date_limite FROM offres WHERE id = :id");
$stmt->execute(['id'=>$id]);
$offre = $stmt->fetch();

if (!$offre) die("Offre inexistante");

// 🔥 état offre
$isExpired = date('Y-m-d') > $offre['date_limite'];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isExpired) {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $domaine = trim($_POST['domaine']);
    $experience = intval($_POST['experience']);
    $niveau = $_POST['niveau'];

    // 🔴 VALIDATION
    if (strlen($nom) < 3) $errors[] = "Nom invalide";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
    if (!preg_match("/^[0-9]{8}$/",$telephone)) $errors[] = "Téléphone invalide";
    if ($experience < 0) $errors[] = "Expérience invalide";

    // 🔴 fichiers
    $maxSize = 2 * 1024 * 1024; // 2MB

    if ($_FILES['cv']['error'] !== 0) $errors[] = "CV obligatoire";
    if ($_FILES['lettre']['error'] !== 0) $errors[] = "Lettre obligatoire";

    if (empty($errors)) {

        if ($_FILES['cv']['type'] != 'application/pdf' ||
            $_FILES['lettre']['type'] != 'application/pdf') {
            $errors[] = "Seulement PDF autorisé";
        }

        if ($_FILES['cv']['size'] > $maxSize ||
            $_FILES['lettre']['size'] > $maxSize) {
            $errors[] = "Fichier trop volumineux (max 2MB)";
        }
    }

    if (empty($errors)) {

        // 🔥 doublon
        $check = $db->prepare("SELECT id FROM candidatures WHERE email=? AND offre_id=?");
        $check->execute([$email,$id]);

        if ($check->rowCount() > 0) {
            $errors[] = "Vous avez déjà postulé";
        } else {

            if (!is_dir("../../uploads")) mkdir("../../uploads");

            $cv = uniqid()."_cv.pdf";
            $lettre = uniqid()."_lettre.pdf";

            move_uploaded_file($_FILES['cv']['tmp_name'],"../../uploads/".$cv);
            move_uploaded_file($_FILES['lettre']['tmp_name'],"../../uploads/".$lettre);

            $sql = "INSERT INTO candidatures 
            (nom,email,cv,lettre,telephone,domaine,experience,niveau,statut,offre_id)
            VALUES (?,?,?,?,?,?,?,?,'en_attente',?)";

            $db->prepare($sql)->execute([
                $nom,$email,$cv,$lettre,$telephone,$domaine,$experience,$niveau,$id
            ]);

            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Postuler</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#F1EFE8; }
.card { border-radius:15px; }
input, select { border-radius:10px !important; }
</style>

</head>

<body>

<div class="container mt-5">

<div class="card shadow-lg p-4">

<h3 class="text-center mb-4">
💼 <?= htmlspecialchars($offre['titre']) ?>
</h3>

<?php if ($isExpired): ?>
<div class="alert alert-danger text-center">
❌ Offre expirée — candidatures fermées
</div>
<a href="index.php" class="btn btn-dark w-100">⬅ Retour</a>

<?php elseif ($success): ?>
<div class="alert alert-success text-center">
🎉 Candidature envoyée avec succès
</div>
<a href="index.php" class="btn btn-dark w-100">⬅ Retour</a>

<?php else: ?>

<?php if ($errors): ?>
<div class="alert alert-danger">
<ul>
<?php foreach($errors as $e): ?>
<li><?= $e ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" novalidate>

<input class="form-control mb-3" name="nom" placeholder="Nom complet">

<input class="form-control mb-3" name="email" placeholder="Email">

<input class="form-control mb-3" name="telephone" placeholder="Téléphone (8 chiffres)">

<input class="form-control mb-3" name="domaine" placeholder="Domaine (ex: Développement Web)">

<input class="form-control mb-3" name="experience" placeholder="Expérience (années)">

<select class="form-control mb-3" name="niveau">
<option value="">Niveau</option>
<option>Licence</option>
<option>Master</option>
<option>Ingénieur</option>
</select>

<label>CV (PDF)</label>
<input type="file" class="form-control mb-3" name="cv">

<label>Lettre (PDF)</label>
<input type="file" class="form-control mb-3" name="lettre">

<button class="btn btn-success w-100">🚀 Envoyer ma candidature</button>

</form>

<a href="index.php" class="btn btn-outline-secondary w-100 mt-3">
⬅ Retour aux offres
</a>

<?php endif; ?>

</div>
</div>

</body>
</html>