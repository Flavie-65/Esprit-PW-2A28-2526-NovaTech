<?php

require_once __DIR__ . '/../Model/Tache.php';
require_once __DIR__ . '/../Model/config.php';

class TacheController {

    // =========================
    // AFFICHER
    // =========================
    public function afficherTaches() {

        $db = config::getConnexion();

        $sql = "SELECT * FROM taches ORDER BY id_tache DESC";

        $stmt = $db->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $taches = [];

        foreach ($rows as $row) {

            $t = new Tache();

            $t->id_tache = $row['id_tache'];
            $t->nom_tache = $row['nom_tache'];
            $t->description = $row['description'];
            $t->statut = $row['statut'];
            $t->priorite = $row['priorite'];
            $t->date_fin = $row['date_fin'];

            $taches[] = $t;
        }

        // =========================
        // STATS
        // =========================

        $stats = [
            'total' => count($taches),
            'urgent' => 0,
            'termine' => 0
        ];

        foreach ($taches as $t) {

            // priorité haute = urgente
            if ($t->priorite == 'Haute') {
                $stats['urgent']++;
            }

            // tâche terminée
            if ($t->statut == 'Terminé') {
                $stats['termine']++;
            }
        }

        require_once __DIR__ . '/../View/BackOffice/tache/liste_taches.php';
    }

    // =========================
    // AJOUTER
    // =========================
    public function ajouterTache() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Vérification simple
            if (
                empty($_POST['nom_tache']) ||
                empty($_POST['description']) ||
                empty($_POST['statut']) ||
                empty($_POST['priorite']) ||
                empty($_POST['date_fin'])
            ) {

                // Retour vers la liste avec erreur
                header("Location: TacheController.php?erreur=1");
                exit();
            }

            $db = config::getConnexion();

            $sql = "INSERT INTO taches
                    (nom_tache, description, statut, priorite, date_fin)
                    VALUES
                    (:nom, :description, :statut, :priorite, :date_fin)";

            $stmt = $db->prepare($sql);

            $stmt->execute([

                ':nom' => $_POST['nom_tache'],
                ':description' => $_POST['description'],
                ':statut' => $_POST['statut'],
                ':priorite' => $_POST['priorite'],
                ':date_fin' => $_POST['date_fin']

            ]);

            // Retour avec succès
            header("Location: TacheController.php?success=1");
            exit();
        }
    }

    // =========================
    // SUPPRIMER
    // =========================
    public function supprimerTache($id) {

        $db = config::getConnexion();

        $sql = "DELETE FROM taches WHERE id_tache = :id";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        header("Location: TacheController.php");
        exit();
    }
}

/* =========================
   EXECUTION
========================= */

$controller = new TacheController();


// AJOUT
if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {

    $controller->ajouterTache();

}

// SUPPRESSION
elseif (isset($_GET['delete'])) {

    $controller->supprimerTache($_GET['delete']);

}

// AFFICHAGE
else {

    $controller->afficherTaches();
}

?>