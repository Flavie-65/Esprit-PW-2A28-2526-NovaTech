<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../Model/config.php';

// 🔹 vérifier ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("❌ Erreur : aucune offre sélectionnée");
}

$db = config::getConnexion();

// 🔹 récupérer titre offre
$offre = $db->prepare("SELECT titre FROM offres WHERE id = :id");
$offre->execute(['id' => $id]);
$data = $offre->fetch();

$success = "";
$error = "";

// 🔹 garder valeurs
$nom = "";
$email = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $offre_id = $_POST['offre_id'] ?? '';

    // 🔐 CONTROLE DE SAISIE
    if (strlen($nom) < 3) {
        $error = "❌ Nom trop court (min 3 caractères)";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Email invalide";
    }
    elseif (strlen($message) < 10) {
        $error = "❌ Message trop court (min 10 caractères)";
    }

    // 📁 Vérifier CV
    elseif (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {

        $fileType = $_FILES['cv']['type'];

        if ($fileType !== 'application/pdf') {
            $error = "❌ Seuls les fichiers PDF sont autorisés";
        } else {

            // 🔥 vérifier doublon AVANT upload
            $check = $db->prepare("SELECT * FROM candidatures WHERE email = :email AND offre_id = :offre_id");
            $check->execute([
                'email' => $email,
                'offre_id' => $offre_id
            ]);

            if ($check->rowCount() > 0) {
                $error = "❌ Vous avez déjà postulé à cette offre";
            } else {

                $cv_name = time() . "_" . basename($_FILES['cv']['name']);
                $cv_tmp = $_FILES['cv']['tmp_name'];
                $upload_path = "../../uploads/" . $cv_name;

                if (!is_dir("../../uploads")) {
                    mkdir("../../uploads", 0777, true);
                }

                if (move_uploaded_file($cv_tmp, $upload_path)) {

                    try {

                        $sql = "INSERT INTO candidatures (nom, email, message, cv, offre_id)
                                VALUES (:nom, :email, :message, :cv, :offre_id)";

                        $query = $db->prepare($sql);
                        $query->execute([
                            'nom' => $nom,
                            'email' => $email,
                            'message' => $message,
                            'cv' => $cv_name,
                            'offre_id' => $offre_id
                        ]);

                        $success = "✅ Candidature envoyée avec succès !";

                        $nom = $email = $message = "";

                    } catch (Exception $e) {
                        $error = "❌ Erreur base de données";
                    }

                } else {
                    $error = "❌ Erreur lors de l'upload du fichier";
                }
            }
        }

    } else {
        $error = "❌ Veuillez ajouter un CV";
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
        body { background-color: #F4F6F9; }

        .card { border-radius: 15px; }

        .btn-success {
            background: linear-gradient(135deg, #1D9E75, #0F6E5E);
            border: none;
        }

        .btn-success:hover {
            background: #0c5c4b;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <div class="card p-4 shadow">

        <!-- 🔥 TITRE OFFRE -->
        <h3 class="mb-4 text-center">
            📩 Postuler à : <?= $data['titre'] ?>
        </h3>

        <!-- SUCCESS -->
        <?php if ($success): ?>
            <div class="alert alert-success text-center">
                <?= $success ?>
            </div>

            <a href="index.php" class="btn btn-dark w-100 mb-3">
                ⬅️ Retour aux offres
            </a>
        <?php endif; ?>

        <!-- ERROR -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <?php if (!$success): ?>
        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="offre_id" value="<?= $id ?>">

            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= $nom ?>" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
            </div>

            <div class="mb-3">
                <label>Lettre de motivation</label>
                <textarea name="message" class="form-control" rows="4" required><?= $message ?></textarea>
            </div>

            <div class="mb-3">
                <label>CV (PDF)</label>
                <input type="file" name="cv" class="form-control" accept=".pdf" required>
            </div>

            <button class="btn btn-success w-100">
                🚀 Envoyer candidature
            </button>

        </form>
        <?php endif; ?>

        <!-- RETOUR -->
        <?php if (!$success): ?>
        <a href="index.php" class="btn btn-outline-secondary w-100 mt-3">
            ⬅️ Retour aux offres
        </a>
        <?php endif; ?>

    </div>
</div>

</body>
</html>