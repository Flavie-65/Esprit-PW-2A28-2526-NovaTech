<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets</title>
    <link rel="stylesheet" href="views/frontoffice/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Liste des Projets</h1>
        <a href="index.php?module=projet&action=ajouter" class="btn-ajouter">
            + Ajouter un projet
        </a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du projet</th>
                    <th>Description</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projets)): ?>
                    <tr>
                        <td colspan="7">Aucun projet trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projets as $projet): ?>
                        <tr>
                            <td><?php echo $projet->getIdProjet(); ?></td>
                            <td><?php echo $projet->getNomProjet(); ?></td>
                            <td><?php echo $projet->getDescription(); ?></td>
                            <td><?php echo $projet->getDateDebut(); ?></td>
                            <td><?php echo $projet->getDateFin(); ?></td>
                            <td><?php echo $projet->getStatut(); ?></td>
                           <td>
    <a href="index.php?module=projet&action=modifier&id_projet=<?php echo $projet->getIdProjet(); ?>">
        Modifier
    </a>
    <a href="index.php?module=projet&action=supprimer&id_projet=<?php echo $projet->getIdProjet(); ?>"
       onclick="return confirm('Supprimer ?')">
        Supprimer
    </a>
</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>