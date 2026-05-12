<link rel="stylesheet" href="views/frontoffice/css/style.css">

<div class="container" style="max-width:700px; margin:auto; padding:30px;">

    <h2 style="margin-bottom:25px; color:#1D9E75;">
        Ajouter une nouvelle tâche
    </h2>

    <!-- MESSAGE ERREUR -->
    <?php if (isset($_GET['erreur'])): ?>

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

    <!-- MESSAGE SUCCESS -->
    <?php if (isset($_GET['success'])): ?>

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

    <form action="index.php?module=tache&action=ajouterPost"
          method="POST"
          style="
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
          ">

        <!-- NOM -->
        <label style="font-weight:bold;">
            Nom de la tâche :
        </label><br>

        <input type="text"
               name="nom_tache"
               style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    margin-bottom:20px;
                    border:1px solid #ccc;
                    border-radius:6px;
               ">

        <!-- DESCRIPTION -->
        <label style="font-weight:bold;">
            Description :
        </label><br>

        <textarea name="description"
                  rows="4"
                  style="
                        width:100%;
                        padding:10px;
                        margin-top:8px;
                        margin-bottom:20px;
                        border:1px solid #ccc;
                        border-radius:6px;
                  "></textarea>

        <!-- DATE DEBUT -->
        <label style="font-weight:bold;">
            Date de début :
        </label><br>

        <input type="date"
               name="date_debut"
               style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    margin-bottom:20px;
                    border:1px solid #ccc;
                    border-radius:6px;
               ">

        <!-- DATE FIN -->
        <label style="font-weight:bold;">
            Date de fin :
        </label><br>

        <input type="date"
               name="date_fin"
               style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    margin-bottom:20px;
                    border:1px solid #ccc;
                    border-radius:6px;
               ">

        <!-- STATUT -->
        <label style="font-weight:bold;">
            Statut :
        </label><br>

        <select name="statut"
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    margin-bottom:20px;
                    border:1px solid #ccc;
                    border-radius:6px;
                ">

            <option value="">-- Choisir un statut --</option>
            <option value="à faire">À faire</option>
            <option value="en cours">En cours</option>
            <option value="terminée">Terminée</option>

        </select>

        <!-- PRIORITE -->
        <label style="font-weight:bold;">
            Priorité :
        </label><br>

        <select name="priorite"
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    margin-bottom:20px;
                    border:1px solid #ccc;
                    border-radius:6px;
                ">

            <option value="">-- Choisir une priorité --</option>
            <option value="basse">Basse</option>
            <option value="moyenne">Moyenne</option>
            <option value="haute">Haute</option>

        </select>

        <!-- PROJET -->
        <label style="font-weight:bold;">
            Projet parent :
        </label><br>

        <select name="id_projet"
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    margin-bottom:25px;
                    border:1px solid #ccc;
                    border-radius:6px;
                ">

            <option value="">-- Choisir un projet --</option>

            <?php foreach ($projets as $p): ?>

                <option value="<?= $p->getIdProjet() ?>">
                    <?= $p->getNomProjet() ?>
                </option>

            <?php endforeach; ?>

        </select>

        <!-- BOUTON -->
        <button type="submit"
                style="
                    background:#1D9E75;
                    color:white;
                    border:none;
                    padding:12px 25px;
                    border-radius:6px;
                    cursor:pointer;
                    font-size:15px;
                    font-weight:bold;
                ">

            Enregistrer la tâche

        </button>

    </form>

</div>