<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Projet</title>
    <!-- Correction du chemin CSS pour le BackOffice -->
    <link rel="stylesheet" href="public/css/style.css"> 
</head>
<body>
    <div class="container">
        <h1>Modifier un Projet</h1>
        
        <form action="index.php?module=projet&action=modifierPost" method="POST" onsubmit="return validerProjet(this);">
            <!-- Utilisation directe des propriétés comme dans votre ProjetController -->
            <input type="hidden" name="id_projet" value="<?php echo $projet->id_projet; ?>">
            
            <div class="form-group">
                <label>Nom du projet :</label>
                <input type="text" name="nom_projet" required 
                       value="<?php echo htmlspecialchars($projet->nom_projet); ?>">
            </div>

            <div class="form-group">
                <label>Description :</label>
                <textarea name="description" required><?php echo htmlspecialchars($projet->description); ?></textarea>
            </div>

            <div class="form-group">
                <label>Date de début :</label>
                <input type="date" name="date_debut" required
                       value="<?php echo $projet->date_debut; ?>">
            </div>

            <div class="form-group">
                <label>Date de fin :</label>
                <input type="date" name="date_fin" required
                       value="<?php echo $projet->date_fin; ?>">
            </div>

            <div class="form-group">
                <label>Statut :</label>
                <select name="statut" required>
                    <option value="en cours" <?php echo ($projet->statut == 'en cours') ? 'selected' : ''; ?>>En cours</option>
                    <option value="terminé" <?php echo ($projet->statut == 'terminé') ? 'selected' : ''; ?>>Terminé</option>
                    <option value="suspendu" <?php echo ($projet->statut == 'suspendu') ? 'selected' : ''; ?>>Suspendu</option>
                </select>
            </div>

            <script>
    function validerProjet(form) {
        var nom = form.nom_projet.value.trim();
        var desc = form.description.value.trim();
        var debut = new Date(form.date_debut.value);
        var fin = new Date(form.date_fin.value);

        if (nom.length < 3) {
            alert("Erreur : Le nom doit faire au moins 3 caractères.");
            return false; // Bloque l'envoi
        }
        if (desc.length < 5) {
            alert("Erreur : La description est trop courte.");
            return false;
        }
        if (fin < debut) {
            alert("Erreur : La date de fin ne peut pas être avant le début !");
            return false;
        }
        return true; // Autorise l'envoi
    }
    </script>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-save">Enregistrer les modifications</button>
                <a href="index.php?module=projet&action=liste" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>