<?php

class Projet {
    public $id_projet;
    public $nom_projet;
    public $description;
    public $date_debut;
    public $date_fin;
    public $statut;

    // Propriétés pour stockés les taches liées au projet
    public $liste_taches = []; 

    public $taux_avancement;

    public function __construct($id = null, $nom = null, $desc = null, $debut = null, $fin = null, $statut = null) {
        $this->id_projet = $id;
        $this->nom_projet = $nom;
        $this->description = $desc;
        $this->date_debut = $debut;
        $this->date_fin = $fin;
        $this->statut = $statut;
    }

    
    public function getIdProjet() {
        return $this->id_projet;
    }

    public function getNomProjet() {
        return $this->nom_projet;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStatut() {
        return $this->statut;
    }
}