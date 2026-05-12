<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="public/css/style.css"> 
</head>
<body>
    <div class="container">
        <h2>Ajouter un Projet</h2>

        <form action="index.php?module=projet&action=ajouterPost" method="POST" onsubmit="return validerProjet(this);">
            
            <div class="form-group">
                <label>Nom du projet :</label>
                <input type="text" name="nom_projet" id="nom_projet" required maxlength="100">
            </div>

            <div class="form-group">
                <label>Description :</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <label>Date de début :</label>
                <input type="date" name="date_debut" id="date_debut" required>
            </div>

            <div class="form-group">
                <label>Date de fin :</label>
                <input type="date" name="date_fin" id="date_fin" required>
            </div>

            <div class="form-group">
                <label>Statut :</label>
                <select name="statut" id="statut" required>
                    <option value="">-- Choisir un statut --</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                    <option value="suspendu">Suspendu</option>
                </select> 
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-save">Ajouter</button>
                <a href="index.php?module=projet&action=liste" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>

    <script>
    function validerProjet(form) {
        // .value suffit ici puisque type="date" garantit le format
        var nom = form.nom_projet.value.trim();
        var desc = form.description.value.trim();
        var debutStr = form.date_debut.value;
        var finStr = form.date_fin.value;

        if (nom.length < 3) {
            alert("Erreur : Le nom doit faire au moins 3 caractères.");
            return false;
        }
        if (desc.length < 5) {
            alert("Erreur : La description doit faire au moins 5 caractères.");
            return false;
        }
        
        if (debutStr === "" || finStr === "") {
            alert("Erreur : Les dates sont obligatoires.");
            return false;
        }

        var debut = new Date(debutStr);
        var fin = new Date(finStr);

        if (fin < debut) {
            alert("Erreur : La date de fin ne peut pas être avant le début !");
            return false;
        }
        return true; 
    }
    </script>
</body>
</html>