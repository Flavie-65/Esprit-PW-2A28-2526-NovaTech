<?php
include_once '../../Controller/OffreController.php';

$offreC = new OffreController();

$errors = [];

// 🔥 TRAITEMENT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $competences = trim($_POST['competences'] ?? '');
    $date_limite = trim($_POST['date_limite'] ?? '');
    $budget = floatval($_POST['budget'] ?? 0);

    // 🔴 VALIDATION TITRE
    if (strlen($titre) < 3) {
        $errors[] = "Le titre doit contenir au moins 3 caractères";
    }

    // 🔴 DESCRIPTION
    if (strlen($description) < 10) {
        $errors[] = "La description est trop courte";
    }

    // 🔴 COMPÉTENCES
    if (strlen($competences) < 3) {
        $errors[] = "Les compétences sont obligatoires";
    }

    // 🔴 DATE
    if (empty($date_limite)) {
        $errors[] = "Date obligatoire";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date_limite)) {
        $errors[] = "Format date invalide";
    } elseif ($date_limite < date('Y-m-d')) {
        $errors[] = "La date doit être dans le futur";
    }

    // 🔴 BUDGET
    if ($budget <= 0) {
        $errors[] = "Le budget doit être supérieur à 0";
    }

    // ✅ SI OK
    if (empty($errors)) {

       $offreC->ajouterOffre([
    'titre' => $titre,
    'description' => $description,
    'competences' => $competences,
    'date_limite' => $date_limite,
    'budget' => $budget
]);

        header('Location: list.php');
        exit();
    }
}

ob_start();
?>

<h2 class="mb-4">Ajouter une offre</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <!-- 🔴 ERREURS -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>

            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="titre" class="form-control"
                       minlength="3" required
                       value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"
                          minlength="10" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Compétences</label>
                <input type="text" name="competences" class="form-control"
                       required
                       placeholder="Ex: PHP, MySQL, JS"
                       value="<?= htmlspecialchars($_POST['competences'] ?? '') ?>">
            </div>

            <!-- 🔥 DATE PRO -->
            <div class="mb-3">
                <label class="form-label">Date limite</label>
                <input type="date" name="date_limite" class="form-control"
                       min="<?= date('Y-m-d'); ?>"
                       required
                       value="<?= htmlspecialchars($_POST['date_limite'] ?? '') ?>">
            </div>

            <!-- 🔥 BUDGET PRO -->
            <div class="mb-3">
                <label class="form-label">Budget (DT)</label>
                <input type="number" name="budget" class="form-control"
                       min="1"
                       step="0.01"
                       required
                       placeholder="Ex: 1500"
                       value="<?= htmlspecialchars($_POST['budget'] ?? '') ?>">
                <small class="text-muted">Le budget doit être supérieur à 0 DT</small>
            </div>

            <button type="submit" class="btn" style="background:#1D9E75;color:white;">
                💾 Enregistrer
            </button>

            <a href="list.php" class="btn btn-secondary">Annuler</a>

        </form>

    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>