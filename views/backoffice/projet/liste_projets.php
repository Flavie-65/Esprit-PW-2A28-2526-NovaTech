<div class="container" style="font-family: Arial, sans-serif;">
    <h1 style="color: #1D9E75; margin-bottom: 5px;">Gestion des Projets</h1>
    
    <div style="margin-bottom: 20px;">
        <a href="index.php?module=projet&action=stats" style="color: #1D9E75; font-size: 13px; text-decoration: none; font-weight: bold;">
            📊 Voir le rapport statistique
        </a>
    </div>

    <div style="background-color: #FFFFFF; border-left: 5px solid #1D9E75; padding: 20px; margin-bottom: 30px; border-top: 1px solid #EEE; border-right: 1px solid #EEE; border-bottom: 1px solid #EEE;">
        <h3 style="margin-top: 0; margin-bottom: 15px; color: #333;">+ Nouveau Projet</h3>
        
        <?php if (!empty($erreurs)): ?>
            <div style="background-color: #FFEEEE; color: #D9534F; padding: 10px; margin-bottom: 15px; border: 1px solid #EBCCD1;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($erreurs as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="formAjouterProjet" action="index.php?module=projet&action=ajouterPost" method="POST">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tr>
                    <td style="padding-right: 10px;">
                        <label style="display:block; font-size: 12px; margin-bottom: 5px; color: #555; font-weight: bold;">Nom</label>
                        <input type="text" name="nom_projet" required style="width: 150px; padding: 8px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding-right: 10px;">
                        <label style="display:block; font-size: 12px; margin-bottom: 5px; color: #555; font-weight: bold;">Description</label>
                        <input type="text" name="description" required style="width: 200px; padding: 8px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding-right: 10px;">
                        <label style="display:block; font-size: 12px; margin-bottom: 5px; color: #555; font-weight: bold;">Date début</label>
                        <input type="date" name="date_debut" required style="width: 140px; padding: 7px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding-right: 10px;">
                        <label style="display:block; font-size: 12px; margin-bottom: 5px; color: #555; font-weight: bold;">Date fin</label>
                        <input type="date" name="date_fin" required style="width: 140px; padding: 7px; border: 1px solid #ccc;">
                    </td>
                    <td style="padding-right: 10px;">
                        <label style="display:block; font-size: 12px; margin-bottom: 5px; color: #555; font-weight: bold;">Statut</label>
                        <select name="statut" style="width: 110px; padding: 8px; border: 1px solid #ccc; background-color: #FFFFFF;">
                            <option value="en cours">En cours</option>
                            <option value="terminé">Terminé</option>
                            <option value="en attente">En attente</option>
                        </select>
                    </td>
                    <td>
                        <input type="submit" value="Ajouter" style="background-color: #1D9E75; color: #FFFFFF; border: none; padding: 10px 20px; font-weight: bold; cursor: pointer;">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #FFFFFF; border: 1px solid #EEE;">
        <thead>
            <tr style="background-color: #1D9E75; color: #FFFFFF; text-align: left;">
                <th style="padding: 15px;">ID</th>
                <th style="padding: 15px;">Nom du Projet</th>
                <th style="padding: 15px;">Période</th>
                <th style="padding: 15px;">Avancement</th>
                <th style="padding: 15px;">Statut</th>
                <th style="padding: 15px; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($projets)): ?>
                <?php foreach ($projets as $index => $p): ?>
                    <tr style="background-color: <?php echo ($index % 2 == 0) ? '#FFFFFF' : '#F4FAF8'; ?>;">
                        <td style="padding: 15px; color: #777; border-bottom: 1px solid #EEE;"><?php echo $p->id_projet; ?></td>
                        <td style="padding: 15px; border-bottom: 1px solid #EEE;">
                            <strong style="color: #333;"><?php echo htmlspecialchars($p->nom_projet); ?></strong><br>
                            <small style="color: #888;"><?php echo htmlspecialchars($p->description); ?></small>
                        </td>
                        <td style="padding: 15px; font-size: 13px; border-bottom: 1px solid #EEE;">
                            <?php echo $p->date_debut; ?> <span style="color: #bbb;">→</span> <?php echo $p->date_fin; ?>
                        </td>
                        
                        <td style="padding: 15px; border-bottom: 1px solid #EEE; vertical-align: middle;">
                            <div style="width: 100px; background-color: #E0E0E0; height: 10px; margin-bottom: 4px; border: 1px solid #CCC;">
                                <div style="width: <?php echo $p->taux_avancement; ?>%; background-color: #1D9E75; height: 100%;"></div>
                            </div>
                            <b style="font-size: 11px; color: #1D9E75;"><?php echo $p->taux_avancement; ?>%</b>
                        </td>

                        <td style="padding: 15px; border-bottom: 1px solid #EEE;">
                            <span style="padding: 5px 10px; font-size: 11px; font-weight: bold; text-transform: uppercase; 
                                background-color: <?php echo ($p->statut == 'terminé') ? '#DFF0D8' : '#FCF8E3'; ?>; 
                                color: <?php echo ($p->statut == 'terminé') ? '#3C763D' : '#8A6D3B'; ?>;">
                                <?php echo htmlspecialchars($p->statut); ?>
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center; border-bottom: 1px solid #EEE;">
                            <a href="index.php?module=export&action=pdf&id_projet=<?php echo $p->id_projet; ?>" target="_blank" style="color: #3498DB; text-decoration: none; font-weight: bold; margin-right: 10px;">📄 PDF</a>
                            <a href="index.php?module=projet&action=modifier&id_projet=<?php echo $p->id_projet; ?>" style="color: #1D9E75; text-decoration: none; font-weight: bold; margin-right: 10px;">Modifier</a>
                            <a href="index.php?module=projet&action=supprimer&id_projet=<?php echo $p->id_projet; ?>" style="color: #E74C3C; text-decoration: none; font-weight: bold;" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="padding: 30px; text-align: center; color: #999;">Aucun projet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>