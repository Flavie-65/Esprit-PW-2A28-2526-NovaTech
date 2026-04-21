<?php
// Utilise bien database.php (le nom de ton fichier)
require_once(__DIR__ . '/config/database.php');
require_once __DIR__ . '/Projet.php';

class ProjetManager {
    private $conn;

    public function __construct() {
        // Appelle la classe config de ton fichier database.php
        $this->conn = config::getConnexion();
    }

    public function ajouterProjet(Projet $projet) {
        $query = "INSERT INTO projets (nom_projet, description, date_debut, date_fin, statut) 
                  VALUES (:nom, :descr, :debut, :fin, :statut)";
        
        $stmt = $this->conn->prepare($query);
        
        // On utilise les getters de ton objet Projet
        $stmt->bindValue(':nom', $projet->getNomProjet());
        $stmt->bindValue(':descr', $projet->getDescription());
        $stmt->bindValue(':debut', $projet->getDateDebut());
        $stmt->bindValue(':fin', $projet->getDateFin());
        $stmt->bindValue(':statut', $projet->getStatut());
        
        return $stmt->execute();
    }

    public function getAllProjets() {
        $projets = [];
        $stmt = $this->conn->query("SELECT * FROM projets");
        while ($row = $stmt->fetch()) {
            $projets[] = new Projet($row['id_projet'], $row['nom_projet'], $row['description'], $row['date_debut'], $row['date_fin'], $row['statut']);
        }
        return $projets;
    }


public function getProjetById($id) {
        $query = "SELECT * FROM projets WHERE id_projet = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch();
        if ($row) {
            return new Projet(
                $row['id_projet'], 
                $row['nom_projet'], 
                $row['description'], 
                $row['date_debut'], 
                $row['date_fin'], 
                $row['statut']
            );
        }
        return null;
    }

    // --- AJOUTE AUSSI LA SUPPRESSION POUR PLUS TARD ---
    public function supprimerProjet($id) {
        $query = "DELETE FROM projets WHERE id_projet = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function modifierProjet(Projet $projet) {
    $query = "UPDATE projets SET 
                nom_projet = :nom, 
                description = :descr, 
                date_debut = :debut, 
                date_fin = :fin, 
                statut = :statut 
              WHERE id_projet = :id";
    
    $stmt = $this->conn->prepare($query);
    
    $stmt->bindValue(':nom', $projet->getNomProjet());
    $stmt->bindValue(':descr', $projet->getDescription());
    $stmt->bindValue(':debut', $projet->getDateDebut());
    $stmt->bindValue(':fin', $projet->getDateFin());
    $stmt->bindValue(':statut', $projet->getStatut());
    $stmt->bindValue(':id', $projet->getIdProjet());
    
    return $stmt->execute();
}

}