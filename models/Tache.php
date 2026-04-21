<?php
class Tache {
    private $id_tache;
    private $nom_tache;
    private $description;
    private $date_debut;
    private $date_fin;
    private $statut;
    private $priorite;
    private $id_projet;
    private $nom_projet;

    // Constructeur
   public function __construct($id_tache, $nom_tache, $description, $date_debut, $date_fin, $statut, $priorite, $id_projet, $nom_projet = null) {
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

    // Getters
    public function getNomProjet() {
        return $this->nom_projet;
    }
    public function getIdTache() {
        return $this->id_tache;
    }
    public function getNomTache() {
        return $this->nom_tache;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getDateDebut() {
        return $this->date_debut;
    }
    public function getDateFin() {
        return $this->date_fin;
    }
    public function getStatut() {
        return $this->statut;
    }
    public function getPriorite() {
        return $this->priorite;
    }
    public function getIdProjet() {
        return $this->id_projet;
    }

    // Setters
    public function setNomTache($nom_tache) {
        $this->nom_tache = $nom_tache;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setDateDebut($date_debut) {
        $this->date_debut = $date_debut;
    }
    public function setDateFin($date_fin) {
        $this->date_fin = $date_fin;
    }
    public function setStatut($statut) {
        $this->statut = $statut;
    }
    public function setPriorite($priorite) {
        $this->priorite = $priorite;
    }
    public function setIdProjet($id_projet) {
        $this->id_projet = $id_projet;
    }
}
?>