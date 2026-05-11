<?php
require_once __DIR__ . '/../models/Projet.php';
require_once __DIR__ . '/../models/Notification.php'; // Importation pour le métier avancé 2
require_once __DIR__ . '/../models/config/database.php'; 

class ProjetController {

    public function afficherProjets() {
        $db = config::getConnexion();
        $projets = [];
        $stmt = $db->query("SELECT * FROM projets");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Projet($row['id_projet'], $row['nom_projet'], $row['description'], $row['date_debut'], $row['date_fin'], $row['statut']);
            $p->taux_avancement = $this->calculerAvancementProjet($p->id_projet);
            $projets[] = $p;
        }
        require_once __DIR__ . '/../views/backoffice/projet/liste_projets.php';
    }

    public function afficherProjetsPublic() {
        $db = config::getConnexion();
        $projets = [];
        $stmt = $db->query("SELECT * FROM projets");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p = new Projet($row['id_projet'], $row['nom_projet'], $row['description'], $row['date_debut'], $row['date_fin'], $row['statut']);
            
            $stmtT = $db->prepare("SELECT * FROM taches WHERE id_projet = :idp");
            $stmtT->execute([':idp' => $p->id_projet]);
            $p->liste_taches = $stmtT->fetchAll(PDO::FETCH_ASSOC);
            
            $p->taux_avancement = $this->calculerAvancementProjet($p->id_projet);
            
            $projets[] = $p;
        }
        require_once __DIR__ . '/../views/frontoffice/liste_projets_public.php';
    }

    public function afficherStats() {
        $stats = Projet::getStats(); 
        require_once __DIR__ . '/../views/backoffice/projet/stats.php';
    }

    private function calculerAvancementProjet($id_projet) {
        $db = config::getConnexion();
        
        $resTotal = $db->prepare("SELECT COUNT(*) as total FROM taches WHERE id_projet = ?");
        $resTotal->execute([$id_projet]);
        $rowTotal = $resTotal->fetch();
        $total = $rowTotal['total'] ?? 0;

        if ($total == 0) return 0;

        $resDone = $db->prepare("SELECT COUNT(*) as done FROM taches WHERE id_projet = ? AND statut = 'Terminé'");
        $resDone->execute([$id_projet]);
        $rowDone = $resDone->fetch();
        $done = $rowDone['done'] ?? 0;

        return round(($done / $total) * 100);
    }

public function ajouterProjet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom_projet']);
            $desc = trim($_POST['description']);
            $debut = $_POST['date_debut'];
            $fin = $_POST['date_fin'];
            $statut = $_POST['statut'];

        
            if (strlen($nom) < 3) {
                echo "<script>alert('Erreur : Le nom doit faire au moins 3 caractères.'); window.history.back();</script>";
                exit();
            }

            if (strlen($desc) < 5) {
                echo "<script>alert('Erreur : La description est trop courte.'); window.history.back();</script>";
                exit();
            }

            if (strtotime($fin) < strtotime($debut)) {
                echo "<script>alert('La date de fin ne peut pas être avant le début !'); window.history.back();</script>";
                exit();
            }

            // --- INSERTION BDD ---
            $db = config::getConnexion();
            $sql = "INSERT INTO projets (nom_projet, description, date_debut, date_fin, statut) 
                    VALUES (:nom, :descr, :debut, :fin, :statut)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom'    => $nom,
                ':descr'  => $desc,
                ':debut'  => $debut,
                ':fin'    => $fin,
                ':statut' => $statut
            ]);

            header('Location: index.php?module=projet&action=liste');
            exit();
        }
    }

    // --- MÉTIER AVANCÉ 2 : Modification avec Notification Email ---
    public function modifierProjet() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Récupération
        $id = $_POST['id_projet'];
        $nom = trim($_POST['nom_projet']);
        $desc = trim($_POST['description']);
        $debut = $_POST['date_debut'];
        $fin = $_POST['date_fin'];
        $statut = $_POST['statut'];

        // 2. CONTRÔLES STRICTS (PHP)
        
        if (strlen($nom) < 3) {
    echo "<script>alert('Erreur : Le nom doit faire au moins 3 caractères.'); window.history.back();</script>";
    exit();
         }
  
        if (strlen($desc) < 5) {
    echo "<script>alert('Erreur : La description est trop courte.'); window.history.back();</script>";
    exit();
}

        if (empty($debut) || empty($fin)) {
    echo "<script>alert('Erreur : Les dates sont obligatoires.'); window.history.back();</script>";
    exit();
}
       if (strtotime($fin) < strtotime($debut)) {
    echo "<script>alert('La date de fin ne peut pas être avant le début !'); window.history.back();</script>";
    exit();
       }

    
        $db = config::getConnexion();
        $sql = "UPDATE projets SET nom_projet = :nom, description = :descr, 
                date_debut = :debut, date_fin = :fin, statut = :statut 
                WHERE id_projet = :id";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom'    => $nom,
            ':descr'  => $desc,
            ':debut'  => $debut,
            ':fin'    => $fin,
            ':statut' => $statut,
            ':id'     => $id
        ]);

        header('Location: index.php?module=projet&action=liste');
        exit();
    }
}

    public function supprimerProjet($id) {
        if (!$id) return;
        $db = config::getConnexion();
        $stmt = $db->prepare("DELETE FROM projets WHERE id_projet = :id");
        $stmt->execute([':id' => $id]);
        header('Location: index.php?module=projet&action=liste');
        exit();
    }

    public function afficherFormulaireModification($id) {
        if (!$id) {
            header('Location: index.php?module=projet&action=liste');
            exit();
        }
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM projets WHERE id_projet = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $projet = new Projet($row['id_projet'], $row['nom_projet'], $row['description'], $row['date_debut'], $row['date_fin'], $row['statut']);
            require_once __DIR__ . '/../views/backoffice/projet/modifier_projet.php';
        } else {
            header('Location: index.php?module=projet&action=liste');
        }
    }
}