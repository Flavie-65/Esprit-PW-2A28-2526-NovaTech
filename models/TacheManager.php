<?php

class TacheManager {
    private $conn;

    public function __construct() {
        // Utilisation de la connexion PDO via la classe config
        $this->conn = config::getConnexion();
    }

    // RÉCUPÉRER TOUTES LES TACHES 
    public function getAllTaches() {
    $taches = array();
    // jointure 
    $query = "SELECT t.*, p.nom_projet 
              FROM taches t 
              INNER JOIN projets p ON t.id_projet = p.id_projet";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tache = new Tache(
            $row['id_tache'],
            $row['nom_tache'],
            $row['description'],
            $row['date_debut'],
            $row['date_fin'],
            $row['statut'],  
            $row['priorite'], 
            $row['id_projet'],
            $row['nom_projet']
        );
        array_push($taches, $tache);
    }
    return $taches;
}

    //RÉCUPÉRER UNE TACHE PAR SON ID
    public function getTacheById($id_tache) {
    $query = "SELECT * FROM taches WHERE id_tache = :id_tache";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':id_tache', $id_tache, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        return new Tache(
            $row['id_tache'],
            $row['nom_tache'],
            $row['description'],
            $row['date_debut'],
            $row['date_fin'],
            $row['statut'],  
            $row['priorite'], 
            $row['id_projet'] 
        );
    }
    return null;
}

    //  AJOUTER UNE TACHE
    public function ajouterTache($tache) {
        $query = "INSERT INTO taches (nom_tache, description, date_debut, date_fin, statut, priorite, id_projet) 
                  VALUES (:nom_tache, :description, :date_debut, :date_fin, :statut, :priorite, :id_projet)";
        
        $stmt = $this->conn->prepare($query);
        
        // bindValue est utilisé ici car on passe des valeurs issues de méthodes (getters)
        $stmt->bindValue(':nom_tache', $tache->getNomTache());
        $stmt->bindValue(':description', $tache->getDescription());
        $stmt->bindValue(':date_debut', $tache->getDateDebut());
        $stmt->bindValue(':date_fin', $tache->getDateFin());
        $stmt->bindValue(':statut', $tache->getStatut());
        $stmt->bindValue(':priorite', $tache->getPriorite());
        $stmt->bindValue(':id_projet', $tache->getIdProjet());
        
        return $stmt->execute();
    }

    //  MODIFIER UNE TACHE
    public function modifierTache($tache) {
        $query = "UPDATE taches SET nom_tache = :nom_tache, description = :description, 
                  date_debut = :date_debut, date_fin = :date_fin, statut = :statut, 
                  priorite = :priorite, id_projet = :id_projet 
                  WHERE id_tache = :id_tache";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindValue(':nom_tache', $tache->getNomTache());
        $stmt->bindValue(':description', $tache->getDescription());
        $stmt->bindValue(':date_debut', $tache->getDateDebut());
        $stmt->bindValue(':date_fin', $tache->getDateFin());
        $stmt->bindValue(':statut', $tache->getStatut());
        $stmt->bindValue(':priorite', $tache->getPriorite());
        $stmt->bindValue(':id_projet', $tache->getIdProjet());
        $stmt->bindValue(':id_tache', $tache->getIdTache(), PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    //SUPPRIMER UNE TACHE
    public function supprimerTache($id_tache) {
        $query = "DELETE FROM taches WHERE id_tache = :id_tache";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id_tache', $id_tache, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>