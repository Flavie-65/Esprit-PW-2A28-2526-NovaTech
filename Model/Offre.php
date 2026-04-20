<?php
class Offre {
    private $id;
    private $titre;
    private $description;
    private $competences;
    private $date_limite;
    private $budget;

    public function __construct($id, $titre, $description, $competences, $date_limite, $budget) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->competences = $competences;
        $this->date_limite = $date_limite;
        $this->budget = $budget;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCompetences() {
        return $this->competences;
    }

    public function getDateLimite() {
        return $this->date_limite;
    }

    public function getBudget() {
        return $this->budget;
    }
}
?>