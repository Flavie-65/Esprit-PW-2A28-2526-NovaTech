<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../Model/config.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Offre invalide");

$db = config::getConnexion();

// 🔹 récupérer offre
$stmt = $db->prepare("SELECT titre, date_limite FROM offres WHERE id = :id");
$stmt->execute(['id'=>$id]);
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offre) die("Offre inexistante");

// 🔹 état offre
$isExpired = date('Y-m-d') > $offre['date_limite'];

$errors = [];
$success = false;

// 🔹 valeurs formulaire
$nom = $_POST['nom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$domaine = $_POST['domaine'] ?? '';
$experience = $_POST['experience'] ?? '';
$niveau = $_POST['niveau'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isExpired) {

    $nom = trim($nom);
    $email = trim($email);
    $telephone = trim($telephone);
    $domaine = trim($domaine);
    $niveau = trim($niveau);

    // 🔴 VALIDATION EXPERIENCE
    if ($experience === '') {
        $errors[] = "Expérience obligatoire";
    } elseif (!ctype_digit($experience)) {
        $errors[] = "Expérience doit être un nombre entier";
    } else {
        $experience = (int)$experience;
    }

    // 🔴 AUTRES VALIDATIONS
    if (strlen($nom) < 3) $errors[] = "Nom invalide";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
    if (!preg_match("/^[0-9]{8}$/",$telephone)) $errors[] = "Téléphone invalide";
    if (empty($niveau)) $errors[] = "Niveau obligatoire";

    // 🔥 dossier upload
    if (!is_dir("../../uploads")) mkdir("../../uploads");

    $maxSize = 2 * 1024 * 1024;

    // 🔹 CV
    if (!isset($_SESSION['cv'])) {
        if ($_FILES['cv']['error'] === 0) {

            if ($_FILES['cv']['type'] !== 'application/pdf') {
                $errors[] = "CV doit être un PDF";
            } elseif ($_FILES['cv']['size'] > $maxSize) {
                $errors[] = "CV trop volumineux (max 2MB)";
            } else {
                $cvName = uniqid()."_cv.pdf";
                move_uploaded_file($_FILES['cv']['tmp_name'], "../../uploads/".$cvName);
                $_SESSION['cv'] = $cvName;
            }

        } else {
            $errors[] = "CV obligatoire";
        }
    }

    // 🔹 Lettre
    if (!isset($_SESSION['lettre'])) {
        if ($_FILES['lettre']['error'] === 0) {

            if ($_FILES['lettre']['type'] !== 'application/pdf') {
                $errors[] = "Lettre doit être un PDF";
            } elseif ($_FILES['lettre']['size'] > $maxSize) {
                $errors[] = "Lettre trop volumineuse (max 2MB)";
            } else {
                $lettreName = uniqid()."_lettre.pdf";
                move_uploaded_file($_FILES['lettre']['tmp_name'], "../../uploads/".$lettreName);
                $_SESSION['lettre'] = $lettreName;
            }

        } else {
            $errors[] = "Lettre obligatoire";
        }
    }

    // 🔥 INSERT
    if (empty($errors)) {

        $cv = $_SESSION['cv'];
        $lettre = $_SESSION['lettre'];

        // 🔹 doublon
        $check = $db->prepare("SELECT id FROM candidatures WHERE email=? AND offre_id=?");
        $check->execute([$email,$id]);

        if ($check->rowCount() > 0) {
            $errors[] = "Vous avez déjà postulé";
        } else {

            $sql = "INSERT INTO candidatures 
            (nom,email,cv,lettre,telephone,domaine,experience,niveau,statut,offre_id)
            VALUES (?,?,?,?,?,?,?,?,'en_attente',?)";

            $db->prepare($sql)->execute([
                $nom,$email,$cv,$lettre,$telephone,$domaine,$experience,$niveau,$id
            ]);

            // 🔥 nettoyage session
            unset($_SESSION['cv']);
            unset($_SESSION['lettre']);

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

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
<ul class="mb-0">
<?php foreach($errors as $e): ?>
<li><?= $e ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<input class="form-control mb-3" name="nom"
value="<?= htmlspecialchars($nom) ?>" placeholder="Nom complet">

<input class="form-control mb-3" name="email"
value="<?= htmlspecialchars($email) ?>" placeholder="Email">

<input class="form-control mb-3" name="telephone"
value="<?= htmlspecialchars($telephone) ?>" placeholder="Téléphone">

<input class="form-control mb-3" name="domaine"
value="<?= htmlspecialchars($domaine) ?>" placeholder="Domaine">

<input class="form-control mb-3" type="number" name="experience"
value="<?= htmlspecialchars($experience) ?>" placeholder="Expérience" min="0">

<select class="form-control mb-3" name="niveau">
<option value="">Niveau</option>
<option <?= ($niveau=='Licence')?'selected':'' ?>>Licence</option>
<option <?= ($niveau=='Master')?'selected':'' ?>>Master</option>
<option <?= ($niveau=='Ingénieur')?'selected':'' ?>>Ingénieur</option>
</select>

<!-- CV -->
<?php if(isset($_SESSION['cv'])): ?>
<div class="text-success">✅ CV déjà uploadé</div>
<?php endif; ?>
<input type="file" class="form-control mb-3" name="cv">

<!-- Lettre -->
<?php if(isset($_SESSION['lettre'])): ?>
<div class="text-success">✅ Lettre déjà uploadée</div>
<?php endif; ?>
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