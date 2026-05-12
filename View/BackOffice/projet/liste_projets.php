<div class="container" style="font-family: Arial, sans-serif; padding:20px;">

    <!-- HEADER -->
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:25px;
    ">
<!-- BOUTON RETOUR ACCUEIL -->
<div style="margin-bottom:15px;">

    <a href="/jobboard/View/BackOffice/dashboard.php"
       style="
            background:#6C757D;
            color:white;
            padding:10px 18px;
            text-decoration:none;
            border-radius:8px;
            font-weight:bold;
       ">

        ← Retour à l'accueil

    </a>

</div>
        <div>

        
            <h1 style="
                color:#1D9E75;
                margin:0;
                font-size:42px;
            ">
                📁 Gestion des Projets
            </h1>

            <p style="
                color:#777;
                margin-top:5px;
            ">
                Gestion complète des projets OrgaSync
            </p>
        </div>

        <!-- BOUTON STATS -->
        <a href="/jobboard/Controller/ProjetController.php?action=stats"
           style="
                background:#1D9E75;
                color:white;
                padding:12px 20px;
                text-decoration:none;
                border-radius:10px;
                font-weight:bold;
                box-shadow:0 2px 8px rgba(0,0,0,0.15);
           ">
            📊 Statistiques
        </a>

    </div>

    <!-- FORMULAIRE AJOUT -->
    <div style="
        background:white;
        padding:25px;
        border-radius:14px;
        margin-bottom:30px;
        box-shadow:0 4px 15px rgba(0,0,0,0.08);
        border-left:5px solid #1D9E75;
    ">

        <h2 style="
            margin-top:0;
            margin-bottom:20px;
            color:#333;
        ">
            ➕ Nouveau Projet
        </h2>

        <!-- FORM -->
        <form action="/jobboard/Controller/ProjetController.php?action=ajouter"
              method="POST">

            <div style="
                display:flex;
                gap:15px;
                flex-wrap:wrap;
                align-items:end;
            ">

                <!-- NOM -->
                <div style="flex:1; min-width:200px;">

                    <label style="
                        display:block;
                        margin-bottom:6px;
                        font-weight:bold;
                    ">
                        Nom
                    </label>

                    <input type="text"
                           name="nom_projet"
                           placeholder="Nom du projet"
                           style="
                                width:100%;
                                padding:12px;
                                border:1px solid #ccc;
                                border-radius:8px;
                           ">

                </div>

                <!-- DESCRIPTION -->
                <div style="flex:1; min-width:250px;">

                    <label style="
                        display:block;
                        margin-bottom:6px;
                        font-weight:bold;
                    ">
                        Description
                    </label>

                    <input type="text"
                           name="description"
                           placeholder="Description du projet"
                           style="
                                width:100%;
                                padding:12px;
                                border:1px solid #ccc;
                                border-radius:8px;
                           ">

                </div>

                <!-- DATE DEBUT -->
                <div>

                    <label style="
                        display:block;
                        margin-bottom:6px;
                        font-weight:bold;
                    ">
                        Début
                    </label>

                    <input type="text"
                           name="date_debut"
                           placeholder="AAAA-MM-JJ"
                           style="
                                padding:12px;
                                border:1px solid #ccc;
                                border-radius:8px;
                           ">

                </div>

                <!-- DATE FIN -->
                <div>

                    <label style="
                        display:block;
                        margin-bottom:6px;
                        font-weight:bold;
                    ">
                        Fin
                    </label>

                    <input type="text"
                           name="date_fin"
                           placeholder="AAAA-MM-JJ"
                           style="
                                padding:12px;
                                border:1px solid #ccc;
                                border-radius:8px;
                           ">

                </div>

                <!-- STATUT -->
                <div>

                    <label style="
                        display:block;
                        margin-bottom:6px;
                        font-weight:bold;
                    ">
                        Statut
                    </label>

                    <select name="statut"
                            style="
                                padding:12px;
                                border:1px solid #ccc;
                                border-radius:8px;
                            ">

                        <option value="en cours">En cours</option>
                        <option value="terminé">Terminé</option>
                        <option value="en attente">En attente</option>

                    </select>

                </div>

                <!-- BOUTON -->
                <div>

                    <button type="submit"
                            style="
                                background:#1D9E75;
                                color:white;
                                border:none;
                                padding:12px 24px;
                                border-radius:10px;
                                font-weight:bold;
                                cursor:pointer;
                            ">

                        ➕ Ajouter

                    </button>

                </div>

            </div>

        </form>

    </div>

    <!-- TABLEAU -->
    <div style="
        background:white;
        border-radius:14px;
        overflow:hidden;
        box-shadow:0 4px 15px rgba(0,0,0,0.08);
    ">

        <table style="
            width:100%;
            border-collapse:collapse;
        ">

            <!-- HEADER -->
            <thead>

                <tr style="
                    background:#1D9E75;
                    color:white;
                    text-align:left;
                ">

                    <th style="padding:18px;">ID</th>
                    <th style="padding:18px;">Projet</th>
                    <th style="padding:18px;">Période</th>
                    <th style="padding:18px;">Avancement</th>
                    <th style="padding:18px;">Statut</th>
                    <th style="padding:18px; text-align:center;">Actions</th>

                </tr>

            </thead>

            <!-- BODY -->
            <tbody>

            <?php if (!empty($projets)): ?>

                <?php foreach ($projets as $index => $p): ?>

                    <tr style="
                        background:<?= ($index % 2 == 0) ? '#FFFFFF' : '#F8FAF9'; ?>;
                        border-bottom:1px solid #EEE;
                    ">

                        <!-- ID -->
                        <td style="
                            padding:18px;
                            color:#666;
                        ">
                            <?= $p->id_projet; ?>
                        </td>

                        <!-- PROJET -->
                        <td style="padding:18px;">

                            <strong style="
                                color:#333;
                                font-size:16px;
                            ">
                                <?= htmlspecialchars($p->nom_projet); ?>
                            </strong>

                            <br>

                            <small style="
                                color:#888;
                            ">
                                <?= htmlspecialchars($p->description); ?>
                            </small>

                        </td>

                        <!-- DATES -->
                        <td style="
                            padding:18px;
                            color:#555;
                        ">

                            <?= $p->date_debut; ?>

                            <span style="color:#AAA;">→</span>

                            <?= $p->date_fin; ?>

                        </td>

                        <!-- AVANCEMENT -->
                        <td style="padding:18px;">

                            <div style="
                                width:140px;
                                height:10px;
                                background:#DDD;
                                border-radius:20px;
                                overflow:hidden;
                                margin-bottom:6px;
                            ">

                                <div style="
                                    width:<?= $p->taux_avancement; ?>%;
                                    height:100%;
                                    background:#1D9E75;
                                "></div>

                            </div>

                            <small style="
                                color:#1D9E75;
                                font-weight:bold;
                            ">
                                <?= $p->taux_avancement; ?>%
                            </small>

                        </td>

                        <!-- STATUT -->
                        <td style="padding:18px;">

                            <?php

                                $bg = '#FFF3CD';
                                $color = '#856404';

                                if ($p->statut == 'terminé') {
                                    $bg = '#D4EDDA';
                                    $color = '#155724';
                                }

                                if ($p->statut == 'en cours') {
                                    $bg = '#D1ECF1';
                                    $color = '#0C5460';
                                }

                            ?>

                            <span style="
                                background:<?= $bg ?>;
                                color:<?= $color ?>;
                                padding:6px 14px;
                                border-radius:20px;
                                font-size:12px;
                                font-weight:bold;
                                text-transform:uppercase;
                            ">

                                <?= htmlspecialchars($p->statut); ?>

                            </span>

                        </td>

                        <!-- ACTIONS -->
                        <td style="
                            padding:18px;
                            text-align:center;
                        ">

                            <!-- PDF -->
                            <a href="/jobboard/Controller/ExportController.php?action=pdf&id_projet=<?= $p->id_projet; ?>"
                               target="_blank"
                               style="
                                    color:#3498DB;
                                    text-decoration:none;
                                    font-weight:bold;
                                    margin-right:10px;
                               ">

                                📄 PDF

                            </a>

                            <!-- MODIFIER -->
                            <a href="/jobboard/Controller/ProjetController.php?action=modifier&id_projet=<?= $p->id_projet; ?>"
                               style="
                                    color:#1D9E75;
                                    text-decoration:none;
                                    font-weight:bold;
                                    margin-right:10px;
                               ">

                                ✏ Modifier

                            </a>

                            <!-- SUPPRIMER -->
                            <a href="/jobboard/Controller/ProjetController.php?action=supprimer&id_projet=<?= $p->id_projet; ?>"
                               onclick="return confirm('Supprimer ce projet ?')"
                               style="
                                    color:#E74C3C;
                                    text-decoration:none;
                                    font-weight:bold;
                               ">

                                🗑 Supprimer

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6"
                        style="
                            padding:40px;
                            text-align:center;
                            color:#999;
                        ">

                        Aucun projet trouvé.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>