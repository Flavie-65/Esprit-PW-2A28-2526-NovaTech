<div class="container" style="font-family: Arial, sans-serif;">

    <h1 style="color: #1D9E75; margin-bottom: 20px;">
        Gestion des Tâches
    </h1>

    <!-- BOUTON RETOUR -->
    <a href="/jobboard/View/BackOffice/list.php"
   style="
        display:inline-block;
        margin-bottom:20px;
        background:#1D9E75;
        color:white;
        padding:10px 18px;
        text-decoration:none;
        border-radius:8px;
        font-weight:bold;
        transition:0.3s;
        box-shadow:0 2px 8px rgba(0,0,0,0.15);
   "
   onmouseover="this.style.background='#147A5C'"
   onmouseout="this.style.background='#1D9E75'">

    ← Retour

</a>
    <?php if (isset($_GET['erreur'])) : ?>

    <div style="
        background:#ffebee;
        color:#c62828;
        padding:12px;
        border-radius:5px;
        margin-bottom:20px;
    ">
        Tous les champs sont obligatoires.
    </div>

    <?php endif; ?>

    <?php if (isset($_GET['success'])) : ?>

    <div style="
        background:#e8f5e9;
        color:#2e7d32;
        padding:12px;
        border-radius:5px;
        margin-bottom:20px;
    ">
        Tâche ajoutée avec succès.
    </div>

    <?php endif; ?>

    <!-- STATISTIQUES -->
    <div style="display: flex; gap: 15px; margin-bottom: 25px;">

        <div style="flex: 1; background: white; padding: 15px; border-radius: 8px; border-top: 4px solid #1D9E75;">
            <span style="color: #888; font-size: 0.8rem; font-weight: bold;">
                TOTAL
            </span>

            <div style="font-size: 1.4rem; font-weight: bold;">
                <?= isset($stats['total']) ? $stats['total'] : 0 ?>
            </div>
        </div>

        <div style="flex: 1; background: white; padding: 15px; border-radius: 8px; border-top: 4px solid #e74c3c;">
            <span style="color: #888; font-size: 0.8rem; font-weight: bold;">
                URGENTES
            </span>

            <div style="font-size: 1.4rem; font-weight: bold; color: #e74c3c;">
                <?= isset($stats['urgent']) ? $stats['urgent'] : 0 ?>
            </div>
        </div>

        <div style="flex: 1; background: white; padding: 15px; border-radius: 8px; border-top: 4px solid #1D9E75;">
            <span style="color: #888; font-size: 0.8rem; font-weight: bold;">
                TERMINÉES
            </span>

            <div style="font-size: 1.4rem; font-weight: bold; color: #1D9E75;">
                <?= isset($stats['termine']) ? $stats['termine'] : 0 ?>
            </div>
        </div>

    </div>

    <!-- FORMULAIRE -->
    <div style="background: white; padding: 20px; margin-bottom: 30px; border-left: 5px solid #1D9E75; border-radius: 5px;">

        <h3 style="margin-bottom: 15px;">
            + Nouvelle Tâche
        </h3>

        <form action="/jobboard/Controller/TacheController.php?action=ajouter"
              method="POST"
              novalidate
              style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">

            <!-- NOM -->
            <div style="flex:1; min-width:180px;">

                <label>Nom</label>

                <input type="text"
                       name="nom_tache"
                       placeholder="Nom tâche"
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">

            </div>

            <!-- DESCRIPTION -->
            <div style="flex:1; min-width:180px;">

                <label>Description</label>

                <input type="text"
                       name="description"
                       placeholder="Description"
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">

            </div>

            <!-- PRIORITE -->
            <div style="width:150px;">

                <label>Priorité</label>

                <select name="priorite"
                        style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">

                    <option value="">Choisir</option>
                    <option value="Basse">Basse</option>
                    <option value="Moyenne">Moyenne</option>
                    <option value="Haute">Haute</option>

                </select>

            </div>

            <!-- STATUT -->
            <div style="width:150px;">

                <label>Statut</label>

                <select name="statut"
                        style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">

                    <option value="">Choisir</option>
                    <option value="En attente">En attente</option>
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>

                </select>

            </div>

            <!-- DATE -->
            <div style="width:180px;">

                <label>Date fin</label>

                <input type="date"
                       name="date_fin"
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">

            </div>

            <!-- BOUTON -->
            <button type="submit"
                    style="background:#1D9E75; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;">

                Ajouter

            </button>

        </form>

    </div>

    <!-- TABLEAU -->
    <table style="width:100%; border-collapse:collapse; background:white;">

        <thead>

            <tr style="background:#1D9E75; color:white;">

                <th style="padding:15px;">Tâche</th>
                <th style="padding:15px;">Description</th>
                <th style="padding:15px;">Urgence</th>
                <th style="padding:15px;">Priorité</th>
                <th style="padding:15px;">Statut</th>
                <th style="padding:15px;">Date fin</th>
                <th style="padding:15px;">Actions</th>

            </tr>

        </thead>

        <tbody>

        <?php if (!empty($taches)) : ?>

            <?php foreach ($taches as $t) : ?>

                <tr style="border-bottom:1px solid #eee;">

                    <td style="padding:15px;">
                        <?= htmlspecialchars($t->nom_tache) ?>
                    </td>

                    <td style="padding:15px;">
                        <?= htmlspecialchars($t->description) ?>
                    </td>

                    <td style="padding:15px;">

                        <?php
                            $urgence = "Normale";

                            if ($t->priorite == "Haute") {
                                $urgence = "Urgente";
                            }

                            if ($t->statut == "Terminé") {
                                $urgence = "Terminée";
                            }
                        ?>

                        <?= $urgence ?>

                    </td>

                    <td style="padding:15px;">
                        <?= htmlspecialchars($t->priorite) ?>
                    </td>

                    <td style="padding:15px;">
                        <?= htmlspecialchars($t->statut) ?>
                    </td>

                    <td style="padding:15px;">
                        <?= htmlspecialchars($t->date_fin) ?>
                    </td>

                    <td style="padding:15px;">

                        <a href="/jobboard/Controller/TacheController.php?delete=<?= $t->id_tache ?>"
                           onclick="return confirm('Supprimer cette tâche ?')"
                           style="color:red; text-decoration:none;">

                            Supprimer

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else : ?>

            <tr>

                <td colspan="7"
                    style="padding:30px; text-align:center; color:#888;">

                    Aucune tâche.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>