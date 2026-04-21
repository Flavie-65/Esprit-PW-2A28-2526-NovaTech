<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="views/frontoffice/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un Projet</h1>
        <form action="index.php?module=projet&action=ajouterPost" method="POST" id="formAjouterProjet">
            <div class="form-group">
                <label>Nom du projet :</label>
                <input type="text" name="nom_projet" id="nom_projet">
                <span class="erreur" id="erreur_nom"></span>
            </div>
            <div class="form-group">
                <label>Description :</label>
                <textarea name="description" id="description"></textarea>
                <span class="erreur" id="erreur_description"></span>
            </div>
            <div class="form-group">
                <label>Date de début :</label>
                <input type="text" name="date_debut" id="date_debut" placeholder="AAAA-MM-JJ">
                <span class="erreur" id="erreur_date_debut"></span>
            </div>
            <div class="form-group">
                <label>Date de fin :</label>
                <input type="text" name="date_fin" id="date_fin" placeholder="AAAA-MM-JJ">
                <span class="erreur" id="erreur_date_fin"></span>
            </div>
            <div class="form-group">
                <label>Statut :</label>
                <select name="statut" id="statut">
                    <option value="">-- Choisir un statut --</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                    <option value="suspendu">Suspendu</option>
                </select>
                <span class="erreur" id="erreur_statut"></span>
            </div>
            <button type="submit">Ajouter</button>
            <a href="index.php?module=projet&action=liste">Annuler</a>
        </form>
    </div>
    <script src="../../public/js/validation_projet.js"></script>
</body>
</html>