<link rel="stylesheet" href="views/frontoffice/css/style.css">

<div class="container">

    <h2>Liste des Tâches</h2>
    
    <a href="index.php?module=tache&action=ajouter" class="btn-ajouter">Ajouter une tâche</a>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Statut</th>
                <th>Priorité</th>
                <th>Projet associé (Jointure)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($taches as $t): ?>
            <tr>
                <td><?= $t->getNomTache() ?></td>
                <td><?= $t->getDescription() ?></td>
                <td><?= $t->getStatut() ?></td>
                <td><?= $t->getPriorite() ?></td>
                <td><strong><?= $t->getNomProjet() ?></strong></td> 
                <td>
                    <a href="index.php?module=tache&action=modifier&id_tache=<?= $t->getIdTache() ?>" class="btn-modifier">Modifier</a>
                    <a href="index.php?module=tache&action=supprimer&id_tache=<?= $t->getIdTache() ?>" class="btn-supprimer" onclick="return confirm('Supprimer cette tache ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div> ```