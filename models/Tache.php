<?php
class Tache {
    public $id_tache;
    public $nom_tache;
    public $description;
    public $date_debut;
    public $date_fin;
    public $statut;
    public $priorite;
    public $id_projet;
    public $nom_projet;
    public $niveau_urgence;

    // Constructeur corrigé avec paramètres optionnels
    public function __construct(
        $id_tache = null, 
        $nom_tache = null, 
        $description = null, 
        $date_debut = null, 
        $date_fin = null, 
        $statut = null, 
        $priorite = null, 
        $id_projet = null, 
        $nom_projet = null
    ) {
        $this->id_tache = $id_tache;
        $this->nom_tache = $nom_tache;
        $this->description = $description;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->statut = $statut;
        $this->priorite = $priorite;
        $this->id_projet = $id_projet;
        $this->nom_projet = $nom_projet; 
    }

    // Getters pour les métiers simples
    public function getStatut() { return $this->statut; }
    public function getDateFin() { return $this->date_fin; }
    public function getPriorite() { return $this->priorite; }
    public function getNomTache() {
    return $this->nom_tache;
}

public function getIdTache() {
    return $this->id_tache;
}

public function getDescription() {
    return $this->description;
}

public function getIdProjet() {
    return $this->id_projet;
}
}