<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Projet</title>
    <link rel="stylesheet" href="views/frontoffice/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un Projet</h1>
        <form action="index.php?module=projet&action=modifierPost" method="POST">
            <input type="hidden" name="id_projet" value="<?php echo $projet->getIdProjet(); ?>">
            <div class="form-group">
                <label>Nom du projet :</label>
                <input type="text" name="nom_projet" id="nom_projet" 
                    value="<?php echo $projet->getNomProjet(); ?>">
                <span class="erreur" id="erreur_nom"></span>
            </div>
            <div class="form-group">
                <label>Description :</label>
                <textarea name="description" id="description">
                    <?php echo $projet->getDescription(); ?>
                </textarea>
                <span class="erreur" id="erreur_description"></span>
            </div>
            <div class="form-group">
                <label>Date de début :</label>
                <input type="text" name="date_debut" id="date_debut" 
                    value="<?php echo $projet->getDateDebut(); ?>">
                <span class="erreur" id="erreur_date_debut"></span>
            </div>
            <div class="form-group">
                <label>Date de fin :</label>
                <input type="text" name="date_fin" id="date_fin" 
                    value="<?php echo $projet->getDateFin(); ?>">
                <span class="erreur" id="erreur_date_fin"></span>
            </div>
            <div class="form-group">
                <label>Statut :</label>
                <select name="statut" id="statut">
                    <option value="">-- Choisir un statut --</option>
                    <option value="en cours" 
                        <?php echo $projet->getStatut() == 'en cours' ? 'selected' : ''; ?>>
                        En cours
                    </option>
                    <option value="terminé"
                        <?php echo $projet->getStatut() == 'terminé' ? 'selected' : ''; ?>>
                        Terminé
                    </option>
                    <option value="suspendu"
                        <?php echo $projet->getStatut() == 'suspendu' ? 'selected' : ''; ?>>
                        Suspendu
                    </option>
                </select>
                <span class="erreur" id="erreur_statut"></span>
            </div>
            <button type="submit">Modifier</button>
            <a href="../../controllers/ProjetController.php?action=liste">Annuler</a>
        </form>
    </div>
    <script src="../../public/js/validation_projet.js"></script>
</body>
</html>