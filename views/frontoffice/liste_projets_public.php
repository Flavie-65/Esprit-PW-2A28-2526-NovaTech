<?php
// Vérification de sécurité pour éviter l'accès direct
if (!isset($projets)) { $projets = []; }
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <h1 style="color: #1D9E75; margin-bottom: 20px;">NOS PROJETS ET TÂCHES</h1>

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background-color: #1D9E75; color: white; text-align: left;">
                <th style="padding: 15px;">Détails du Projet</th>
                <th style="padding: 15px;">Période</th>
                <th style="padding: 15px;">Statut Projet</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($projets)): ?>
                <?php foreach ($projets as $index => $p): ?>
                    <tr style="background-color: <?= ($index % 2 == 0) ? '#FFFFFF' : '#F4FAF8'; ?>; border-bottom: 1px solid #eee; vertical-align: top;">
                        <td style="padding: 15px;">
                            <strong style="color: #333; font-size: 1.1rem;"><?= htmlspecialchars($p->nom_projet); ?></strong><br>
                            <p style="color: #666; margin-bottom: 10px;"><?= htmlspecialchars($p->description); ?></p>
                            
                            <!-- AFFICHAGE DES TÂCHES ASSOCIÉES -->
                            <div style="background: rgba(29, 158, 117, 0.05); padding: 10px; border-radius: 5px; margin-top: 10px;">
                                <span style="font-size: 0.8rem; font-weight: bold; color: #1D9E75; text-transform: uppercase;">📋 Tâches sur ce projet :</span>
                                <?php if (!empty($p->liste_taches)): ?>
                                    <ul style="margin: 5px 0 0 15px; padding: 0; font-size: 0.85rem;">
                                        <?php foreach ($p->liste_taches as $tache): ?>
                                            <li style="margin-bottom: 3px; color: #444;">
                                                <strong><?= htmlspecialchars($tache['nom_tache']) ?></strong> 
                                                <span style="color: #888; font-size: 0.75rem;">— Statut : <?= htmlspecialchars($tache['statut']) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: #aaa; font-style: italic;">Aucune tâche enregistrée pour ce projet.</p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 15px; font-size: 0.9rem; color: #555;">
                            Du <?= $p->date_debut; ?><br>au <?= $p->date_fin; ?>
                        </td>
                        <td style="padding: 15px;">
                            <span style="padding: 5px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: bold; background: #dff0d8; color: #3c763d; text-transform: uppercase;">
                                <?= htmlspecialchars($p->statut); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="padding: 30px; text-align: center; color: #999;">Aucun projet disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>