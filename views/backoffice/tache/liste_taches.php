<div class="container" style="font-family: Arial, sans-serif;">
    <h1 style="color: #1D9E75; margin-bottom: 20px;">Gestion des Tâches</h1>

    <!-- BLOC STATISTIQUES (Métier Simple) -->
    <div style="display: flex; gap: 15px; margin-bottom: 25px;">
        <div style="flex: 1; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 4px solid #1D9E75;">
            <span style="color: #888; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">Total</span>
            <div style="font-size: 1.4rem; font-weight: bold; color: #333;"><?= $stats['total'] ?></div>
        </div>
        <div style="flex: 1; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 4px solid #e74c3c;">
            <span style="color: #888; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">⚠️ Urgentes</span>
            <div style="font-size: 1.4rem; font-weight: bold; color: #e74c3c;"><?= $stats['urgent'] ?></div>
        </div>
        <div style="flex: 1; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 4px solid #1D9E75;">
            <span style="color: #888; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">✅ Terminées</span>
            <div style="font-size: 1.4rem; font-weight: bold; color: #1D9E75;"><?= $stats['termine'] ?></div>
        </div>
    </div>

    <div style="background: white; border-left: 5px solid #1D9E75; padding: 20px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 4px;">
        <h3 style="margin-top: 0; margin-bottom: 15px; color: #333;">+ Nouvelle Tâche</h3>
        <form action="index.php?module=tache&action=ajouterPost" method="POST" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            
            <div style="flex: 1; min-width: 150px;">
                <label style="display:block; font-size: 0.85rem; margin-bottom: 5px; color: #555; font-weight: bold;">Nom</label>
                <input type="text" name="nom_tache" placeholder="Nom de la tâche" required minlength="4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label style="display:block; font-size: 0.85rem; margin-bottom: 5px; color: #555; font-weight: bold;">Projet Associé</label>
                <select name="id_projet" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: white;">
                    <option value="">-- Choisir un projet --</option>
                    <?php foreach ($projets as $p): ?>
                        <option value="<?= $p->id_projet ?>"><?= htmlspecialchars($p->nom_projet) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 120px;">
                <label style="display:block; font-size: 0.85rem; margin-bottom: 5px; color: #555; font-weight: bold;">Priorité</label>
                <select name="priorite" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: white;">
                    <option value="Basse">Basse</option>
                    <option value="Moyenne" selected>Moyenne</option>
                    <option value="Haute">Haute</option>
                </select>
            </div>

            <div style="width: 120px;">
                <label style="display:block; font-size: 0.85rem; margin-bottom: 5px; color: #555; font-weight: bold;">Statut</label>
                <select name="statut" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: white;">
                    <option value="En attente">En attente</option>
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                </select>
            </div>

            <button type="submit" style="background: #1D9E75; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">Ajouter</button>
        </form>
    </div>

    <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden;">
        <thead>
            <tr style="background-color: #1D9E75; color: white; text-align: left;">
                <th style="padding: 15px;">Tâche</th>
                <th style="padding: 15px;">Projet associé</th>
                <th style="padding: 15px;">Urgence</th>
                <th style="padding: 15px;">Priorité</th>
                <th style="padding: 15px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($taches)): ?>
                <?php foreach ($taches as $index => $t): ?>
                    <tr style="background-color: <?= ($index % 2 == 0) ? '#FFFFFF' : '#F4FAF8'; ?>; border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">
                            <strong style="color: #333;"><?= htmlspecialchars($t->nom_tache) ?></strong>
                        </td>
                        <td style="padding: 15px;">
                            <span style="color: #1D9E75; font-weight: bold;">📁 <?= htmlspecialchars($t->nom_projet) ?></span>
                        </td>
                        <td style="padding: 15px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;
                                background: <?= ($t->niveau_urgence == 'En cours') ? '#e3f2fd' : (($t->getStatut() == 'Terminé') ? '#f1f8e9' : '#ffebee'); ?>;
                                color: <?= ($t->niveau_urgence == 'En cours') ? '#1976d2' : (($t->getStatut() == 'Terminé') ? '#388e3c' : '#c62828'); ?>;">
                                <?= $t->niveau_urgence ?>
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <span style="color: <?= ($t->priorite == 'Haute') ? '#e74c3c' : '#555'; ?>; font-weight: bold;">
                                <?= htmlspecialchars($t->priorite) ?>
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="index.php?module=tache&action=modifier&id_tache=<?= $t->id_tache ?>" style="color: #1D9E75; text-decoration: none; font-weight: bold; margin-right: 10px;">Modifier</a>
                            <a href="index.php?module=tache&action=supprimer&id_tache=<?= $t->id_tache ?>" style="color: #e74c3c; text-decoration: none; font-weight: bold;" onclick="return confirm('Supprimer cette tâche ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="padding: 30px; text-align: center; color: #999;">Aucune tâche.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>