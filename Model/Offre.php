<?php
class Offre {
    private $id;
    private $titre;
    private $description;

    // constructeur
    public function __construct($id, $titre, $description) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
    }

    // getters
    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }
}
?>