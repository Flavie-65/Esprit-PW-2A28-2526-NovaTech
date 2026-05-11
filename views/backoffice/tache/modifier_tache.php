<div style="background: white; border-left: 5px solid #1D9E75; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 4px; max-width: 600px; margin: 20px auto;">
    <h2 style="color: #333; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
        Modifier la tâche : <span style="color: #1D9E75;"><?php echo htmlspecialchars($tache->getNomTache()); ?></span>
    </h2>

    <?php if (isset($erreur)): ?>
        <div style="background: #fee; color: #d9534f; padding: 10px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #ebccd1;">
            <?php echo htmlspecialchars($erreur); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?module=tache&action=modifierPost" method="POST">
        <input type="hidden" name="id_tache" value="<?php echo $tache->id_tache; ?>">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #555;">Nom de la tâche :</label>
            <input type="text" name="nom_tache" value="<?php echo htmlspecialchars($tache->getNomTache()); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #555;">Description :</label>
            <textarea name="description" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"><?php echo htmlspecialchars($tache->getDescription()); ?></textarea>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #555;">Statut :</label>
                <select name="statut" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="En attente" <?php echo ($tache->getStatut() == 'En attente') ? 'selected' : ''; ?>>En attente</option>
                    <option value="En cours" <?php echo ($tache->getStatut() == 'En cours') ? 'selected' : ''; ?>>En cours</option>
                    <option value="Terminé" <?php echo ($tache->getStatut() == 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #555;">Projet associé :</label>
                <select name="id_projet" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <?php foreach ($projets as $p): ?>
                        <option value="<?php echo $p->getIdProjet(); ?>" <?php echo ($p->getIdProjet() == $tache->getIdProjet()) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p->getNomProjet()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
    <div style="flex: 1;">
        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #555;">Priorité :</label>
        <select name="priorite" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="Basse" <?php echo ($tache->getPriorite() == 'Basse') ? 'selected' : ''; ?>>Basse</option>
            <option value="Moyenne" <?php echo ($tache->getPriorite() == 'Moyenne') ? 'selected' : ''; ?>>Moyenne</option>
            <option value="Haute" <?php echo ($tache->getPriorite() == 'Haute') ? 'selected' : ''; ?>>Haute</option>
        </select>
    </div>
    <div style="flex: 1;">
        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #555;">Date d'échéance :</label>
        <input type="date" name="date_fin" value="<?php echo $tache->getDateFin(); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
</div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
            <a href="index.php?module=tache&action=liste" style="color: #666; text-decoration: none;">← Retour</a>
            <button type="submit" style="background: #1D9E75; color: white; border: none; padding: 12px 25px; border-radius: 4px; cursor: pointer; font-weight: bold;">Enregistrer</button>
        </div>
    </form>
</div>