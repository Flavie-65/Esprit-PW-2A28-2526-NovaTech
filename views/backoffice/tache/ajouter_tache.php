<link rel="stylesheet" href="views/frontoffice/css/style.css">

<div class="container">
    <h2>Ajouter une nouvelle tâche</h2>
    </div>

<?php if (isset($erreur)): ?>
    <p style="color: red;"><?= $erreur ?></p>
<?php endif; ?>

<form action="index.php?module=tache&action=ajouterPost" method="POST">
    <label>Nom de la tâche :</label><br>
    <input type="text" name="nom_tache"><br><br> 

    <label>Description :</label><br>
    <textarea name="description"></textarea><br><br>

    <label>Date de début :</label><br>
    <input type="date" name="date_debut"><br><br>

    <label>Date de fin :</label><br>
    <input type="date" name="date_fin"><br><br>

    <label>Statut :</label><br>
    <select name="statut">
        <option value="">-- Choisir un statut --</option>
        <option value="à faire">À faire</option>
        <option value="en cours">En cours</option>
        <option value="terminée">Terminée</option>
    </select><br><br>

    <label>Priorité :</label><br>
    <select name="priorite">
        <option value="">-- Choisir une priorité --</option>
        <option value="basse">Basse</option>
        <option value="moyenne">Moyenne</option>
        <option value="haute">Haute</option>
    </select><br><br>

    <label>Projet parent :</label><br>
    <select name="id_projet">
        <option value="">-- Choisir un projet --</option>
        <?php foreach ($projets as $p): ?>
            <option value="<?= $p->getIdProjet() ?>">
                <?= $p->getNomProjet() ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Enregistrer la tâche</button>
</form>