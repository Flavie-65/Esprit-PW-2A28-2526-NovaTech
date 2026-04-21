<h2>Modifier la tâche</h2>

<?php if (isset($erreur)): ?>
    <p style="color: red;"><?= $erreur ?></p>
<?php endif; ?>

<form action="index.php?module=tache&action=modifierPost" method="POST">
    <input type="hidden" name="id_tache" value="<?= $tache->getIdTache() ?>">

    <label>Nom de la tâche :</label><br>
    <input type="text" name="nom_tache" value="<?= $tache->getNomTache() ?>"><br><br>

    <label>Description :</label><br>
    <textarea name="description"><?= $tache->getDescription() ?></textarea><br><br>

    <label>Statut :</label><br>
    <select name="statut">
        <option value="En cours" <?= ($tache->getStatut() == 'En cours') ? 'selected' : '' ?>>En cours</option>
        <option value="Terminé" <?= ($tache->getStatut() == 'Terminé') ? 'selected' : '' ?>>Terminé</option>
        <option value="En attente" <?= ($tache->getStatut() == 'En attente') ? 'selected' : '' ?>>En attente</option>
    </select><br><br>

    <label>Projet associé :</label><br>
    <select name="id_projet">
        <?php foreach ($projets as $p): ?>
            <option value="<?= $p->getIdProjet() ?>" <?= ($p->getIdProjet() == $tache->getIdProjet()) ? 'selected' : '' ?>>
                <?= $p->getNomProjet() ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Enregistrer les modifications</button>
</form>