<?php

require_once __DIR__ . '/../Model/Projet.php';
require_once __DIR__ . '/../Model/config.php';

class ProjetController
{
    // =========================
    // AFFICHER LES PROJETS
    // =========================
    public function afficherProjets()
    {
        $db = config::getConnexion();

        $projets = [];

        $stmt = $db->query("SELECT * FROM projets");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $p = new Projet(
                $row['id_projet'],
                $row['nom_projet'],
                $row['description'],
                $row['date_debut'],
                $row['date_fin'],
                $row['statut']
            );

            $p->taux_avancement =
                $this->calculerAvancementProjet($p->id_projet);

            $projets[] = $p;
        }

        require_once __DIR__ . '/../View/BackOffice/projet/liste_projets.php';
    }

    // =========================
    // AFFICHAGE PUBLIC
    // =========================
    public function afficherProjetsPublic()
    {
        $db = config::getConnexion();

        $projets = [];

        $stmt = $db->query("SELECT * FROM projets");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $p = new Projet(
                $row['id_projet'],
                $row['nom_projet'],
                $row['description'],
                $row['date_debut'],
                $row['date_fin'],
                $row['statut']
            );

            // Récupérer les tâches
            $stmtT = $db->prepare(
                "SELECT * FROM taches WHERE id_projet = :idp"
            );

            $stmtT->execute([
                ':idp' => $p->id_projet
            ]);

            $p->liste_taches =
                $stmtT->fetchAll(PDO::FETCH_ASSOC);

            $p->taux_avancement =
                $this->calculerAvancementProjet($p->id_projet);

            $projets[] = $p;
        }

        require_once __DIR__ . '/../View/frontoffice/liste_projets_public.php';
    }

    // =========================
    // STATISTIQUES
    // =========================
    public function afficherStats()
    {
        $stats = Projet::getStats();

        require_once __DIR__ . '/../View/BackOffice/projet/stats.php';
    }

    // =========================
    // CALCUL AVANCEMENT
    // =========================
    private function calculerAvancementProjet($id_projet)
    {
        $db = config::getConnexion();

        // Total tâches
        $resTotal = $db->prepare(
            "SELECT COUNT(*) AS total
             FROM taches
             WHERE id_projet = ?"
        );

        $resTotal->execute([$id_projet]);

        $rowTotal = $resTotal->fetch();

        $total = $rowTotal['total'] ?? 0;

        if ($total == 0) {
            return 0;
        }

        // Tâches terminées
        $resDone = $db->prepare(
            "SELECT COUNT(*) AS done
             FROM taches
             WHERE id_projet = ?
             AND statut = 'Terminé'"
        );

        $resDone->execute([$id_projet]);

        $rowDone = $resDone->fetch();

        $done = $rowDone['done'] ?? 0;

        return round(($done / $total) * 100);
    }

    // =========================
    // AJOUTER PROJET
    // =========================
    public function ajouterProjet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nom = trim($_POST['nom_projet']);
            $desc = trim($_POST['description']);
            $debut = $_POST['date_debut'];
            $fin = $_POST['date_fin'];
            $statut = $_POST['statut'];

            // VALIDATIONS

            if (strlen($nom) < 3) {

                echo "
                <script>
                    alert('Le nom doit contenir au moins 3 caractères');
                    window.history.back();
                </script>
                ";

                exit();
            }

            if (strlen($desc) < 5) {

                echo "
                <script>
                    alert('Description trop courte');
                    window.history.back();
                </script>
                ";

                exit();
            }

            if (strtotime($fin) < strtotime($debut)) {

                echo "
                <script>
                    alert('La date de fin doit être après la date début');
                    window.history.back();
                </script>
                ";

                exit();
            }

            $db = config::getConnexion();

            $sql = "
                INSERT INTO projets
                (
                    nom_projet,
                    description,
                    date_debut,
                    date_fin,
                    statut
                )
                VALUES
                (
                    :nom,
                    :descr,
                    :debut,
                    :fin,
                    :statut
                )
            ";

            $stmt = $db->prepare($sql);

            $stmt->execute([
                ':nom'    => $nom,
                ':descr'  => $desc,
                ':debut'  => $debut,
                ':fin'    => $fin,
                ':statut' => $statut
            ]);

            header(
                'Location: /jobboard/Controller/ProjetController.php?action=liste'
            );

            exit();
        }
    }

    // =========================
    // MODIFIER PROJET
    // =========================
    public function modifierProjet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id_projet'];

            $nom = trim($_POST['nom_projet']);
            $desc = trim($_POST['description']);
            $debut = $_POST['date_debut'];
            $fin = $_POST['date_fin'];
            $statut = $_POST['statut'];

            // VALIDATIONS

            if (strlen($nom) < 3) {

                echo "
                <script>
                    alert('Le nom doit contenir au moins 3 caractères');
                    window.history.back();
                </script>
                ";

                exit();
            }

            if (strlen($desc) < 5) {

                echo "
                <script>
                    alert('Description trop courte');
                    window.history.back();
                </script>
                ";

                exit();
            }

            if (strtotime($fin) < strtotime($debut)) {

                echo "
                <script>
                    alert('La date de fin doit être après la date début');
                    window.history.back();
                </script>
                ";

                exit();
            }

            $db = config::getConnexion();

            $sql = "
                UPDATE projets
                SET
                    nom_projet = :nom,
                    description = :descr,
                    date_debut = :debut,
                    date_fin = :fin,
                    statut = :statut
                WHERE id_projet = :id
            ";

            $stmt = $db->prepare($sql);

            $stmt->execute([
                ':nom'    => $nom,
                ':descr'  => $desc,
                ':debut'  => $debut,
                ':fin'    => $fin,
                ':statut' => $statut,
                ':id'     => $id
            ]);

            header(
                'Location: /jobboard/Controller/ProjetController.php?action=liste'
            );

            exit();
        }
    }

    // =========================
    // SUPPRIMER PROJET
    // =========================
    public function supprimerProjet($id)
    {
        if (!$id) {
            return;
        }

        $db = config::getConnexion();

        $stmt = $db->prepare(
            "DELETE FROM projets
             WHERE id_projet = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        header(
            'Location: /jobboard/Controller/ProjetController.php?action=liste'
        );

        exit();
    }

    // =========================
    // FORMULAIRE MODIFICATION
    // =========================
    public function afficherFormulaireModification($id)
    {
        if (!$id) {

            header(
                'Location: /jobboard/Controller/ProjetController.php?action=liste'
            );

            exit();
        }

        $db = config::getConnexion();

        $stmt = $db->prepare(
            "SELECT * FROM projets
             WHERE id_projet = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            $projet = new Projet(
                $row['id_projet'],
                $row['nom_projet'],
                $row['description'],
                $row['date_debut'],
                $row['date_fin'],
                $row['statut']
            );

            require_once __DIR__ . '/../View/BackOffice/projet/modifier_projet.php';

        } else {

            header(
                'Location: /jobboard/Controller/ProjetController.php?action=liste'
            );
        }
    }
}

// =========================
// ROUTER
// =========================

$controller = new ProjetController();

if (isset($_GET['action'])) {

    switch ($_GET['action']) {

        // AJOUTER
        case 'ajouter':

            $controller->ajouterProjet();

            break;

        // LISTE
        case 'liste':

            $controller->afficherProjets();

            break;

        // MODIFIER
        case 'modifier':

            // POST => sauvegarder modification
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $controller->modifierProjet();
            }

            // GET => afficher formulaire
            else if (isset($_GET['id_projet'])) {

                $controller->afficherFormulaireModification(
                    $_GET['id_projet']
                );
            }

            break;

        // SUPPRIMER
        case 'supprimer':

            if (isset($_GET['id_projet'])) {

                $controller->supprimerProjet(
                    $_GET['id_projet']
                );
            }

            break;

        // STATS
        case 'stats':

            $controller->afficherStats();

            break;

        // PAR DÉFAUT
        default:

            $controller->afficherProjets();

            break;
    }

} else {

    $controller->afficherProjets();
}

?>