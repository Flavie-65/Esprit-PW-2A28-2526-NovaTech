<?php
require_once __DIR__ . '/../models/Tache.php';
require_once __DIR__ . '/../models/Projet.php';
require_once __DIR__ . '/../models/config/database.php';

class TacheController {

    public function afficherTaches() {
        $db = config::getConnexion();
        
        $sql = "SELECT t.*, p.nom_projet 
                FROM taches t 
                LEFT JOIN projets p ON t.id_projet = p.id_projet";
        $stmt = $db->query($sql);
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $taches = [];

        foreach ($rows as $row) {
            $t = new Tache(); 
            $t->id_tache = $row['id_tache'];
            $t->nom_tache = $row['nom_tache'];
            $t->description = $row['description'] ?? '';
            $t->id_projet = $row['id_projet'];
            $t->statut = $row['statut'];
            $t->priorite = $row['priorite'];
            $t->date_fin = $row['date_fin'];
            $t->nom_projet = $row['nom_projet'] ?? 'Aucun projet';
            $t->niveau_urgence = $this->calculerUrgence($t->date_fin, $t->statut);
            $taches[] = $t;
        }

        $stats = $this->calculerStats($taches);
        
        $stmtP = $db->query("SELECT * FROM projets");
        $projets_rows = $stmtP->fetchAll(PDO::FETCH_ASSOC);
        $projets = [];
        foreach ($projets_rows as $p_row) {
            $p = new Projet();
            $p->id_projet = $p_row['id_projet'];
            $p->nom_projet = $p_row['nom_projet'];
            $projets[] = $p;
        }

        require_once __DIR__ . '/../views/backoffice/tache/liste_taches.php';
    }

    private function calculerUrgence($date_fin, $statut) {
        if ($statut === 'Terminé') return 'Terminé';
        if (empty($date_fin) || $date_fin === '0000-00-00') return 'Non définie';
        try {
            $dateEcheance = new DateTime($date_fin);
            $aujourdhui = new DateTime();
            $intervalle = $aujourdhui->diff($dateEcheance);
            $joursRestants = (int)$intervalle->format('%r%a');
            if ($joursRestants < 0) return 'En retard';
            if ($joursRestants <= 2) return 'Urgent';
            return 'En cours';
        } catch (Exception $e) { return 'Date invalide'; }
    }

    private function calculerStats($taches) {
        $stats = ['total' => count($taches), 'urgent' => 0, 'termine' => 0];
        foreach ($taches as $t) {
            if ($t->niveau_urgence === 'Urgent') $stats['urgent']++;
            if ($t->statut === 'Terminé') $stats['termine']++;
        }
        return $stats;
    }

    public function ajouterTache() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom_tache']);
        $id_p = $_POST['id_projet'];
        $prio = $_POST['priorite'];
        $fin = $_POST['date_fin'];

        // CONTROLES
        if (strlen($nom) < 3) {
            echo "<script>alert('Erreur : Le nom de la tâche doit faire au moins 3 caractères.'); window.history.back();</script>";
            exit();
        }
        if (empty($id_p)) {
            echo "<script>alert('Erreur : Veuillez sélectionner un projet.'); window.history.back();</script>";
            exit();
        }

        $db = config::getConnexion();
        $sql = "INSERT INTO taches (nom_tache, description, id_projet, priorite, statut, date_fin) 
                VALUES (:nom, :descr, :idp, :prio, :statut, :fin)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom'    => $nom,
            ':descr'  => $_POST['description'] ?? '',
            ':idp'    => $id_p,
            ':prio'   => $prio,
            ':statut' => $_POST['statut'],
            ':fin'    => $fin
        ]);
        header('Location: index.php?module=tache&action=liste');
        exit();
    }
}



    public function supprimerTache($id) {
        if ($id) {
            $db = config::getConnexion();
            $stmt = $db->prepare("DELETE FROM taches WHERE id_tache = :id");
            $stmt->execute([':id' => $id]);
        }
        header('Location: index.php?module=tache&action=liste');
        exit();
    }

    public function afficherFormulaireModification($id) {
        if (!$id) {
            header('Location: index.php?module=tache&action=liste');
            exit();
        }
        $db = config::getConnexion();
        
        $stmt = $db->prepare("SELECT * FROM taches WHERE id_tache = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $tache = new Tache();
            $tache->id_tache = $row['id_tache'];
            $tache->nom_tache = $row['nom_tache'];
            $tache->description = $row['description'];
            $tache->id_projet = $row['id_projet'];
            $tache->statut = $row['statut'];
            $tache->priorite = $row['priorite'];
            $tache->date_fin = $row['date_fin'];

            $stmtP = $db->query("SELECT * FROM projets");
            $projets_rows = $stmtP->fetchAll(PDO::FETCH_ASSOC);
            $projets = [];
            foreach ($projets_rows as $p_row) {
                $p = new Projet();
                $p->id_projet = $p_row['id_projet'];
                $p->nom_projet = $p_row['nom_projet'];
                $projets[] = $p;
            }

            require_once __DIR__ . '/../views/backoffice/tache/modifier_tache.php';
        } else {
            header('Location: index.php?module=tache&action=liste');
        }
    }


    public function modifierPost() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom_tache']);
        $id_p = $_POST['id_projet'];
        $prio = $_POST['priorite'];
        $fin = $_POST['date_fin'];


        if (empty($nom) || strlen($nom) < 3) {
            echo "<script>alert('Erreur : Nom de tâche invalide (min 3 car.).'); window.history.back();</script>";
            exit();
        }

        $priorites_valides = ['Basse', 'Moyenne', 'Haute'];
        if (!in_array($prio, $priorites_valides)) {
            echo "<script>alert('Erreur : Priorité invalide.'); window.history.back();</script>";
            exit();
        }

        $db = config::getConnexion();
        $sql = "UPDATE taches SET nom_tache = :nom, description = :descr, id_projet = :idp, 
                priorite = :prio, statut = :statut, date_fin = :fin WHERE id_tache = :id";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom'   => $nom,
            ':descr' => $_POST['description'],
            ':idp'   => $id_p,
            ':prio'  => $prio,
            ':statut'=> $_POST['statut'],
            ':fin'   => $fin,
            ':id'    => $_POST['id_tache']
        ]);

        header('Location: index.php?module=tache&action=liste');
        exit();
    }
}
}