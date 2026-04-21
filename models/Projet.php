<?php
class Projet {
    private $id_projet;
    private $nom_projet;
    private $description;
    private $date_debut;
    private $date_fin;
    private $statut;

    // Constructeur
    public function __construct($id_projet, $nom_projet, $description, $date_debut, $date_fin, $statut) {
        $this->id_projet = $id_projet;
        $this->nom_projet = $nom_projet;
        $this->description = $description;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->statut = $statut;
    }

    // Getters
    public function getIdProjet() {
        return $this->id_projet;
    }
    public function getNomProjet() {
        return $this->nom_projet;
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

    // Setters
    public function setNomProjet($nom_projet) {
        $this->nom_projet = $nom_projet;
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
}
?>