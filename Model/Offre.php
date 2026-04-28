<?php
include_once 'config.php';

class Offre {

    private $titre;
    private $description;
    private $competences;
    private $date_limite;
    private $budget;

    function __construct($titre, $description, $competences, $date_limite, $budget) {
        $this->titre = $titre;
        $this->description = $description;
        $this->competences = $competences;
        $this->date_limite = $date_limite;
        $this->budget = $budget;
    }

    // 🔹 Ajouter
    function ajouter() {
        $db = config::getConnexion();

        $sql = "INSERT INTO offres (titre, description, competences, date_limite, budget)
                VALUES (:titre, :description, :competences, :date_limite, :budget)";

        $req = $db->prepare($sql);
        $req->execute([
            'titre'=>$this->titre,
            'description'=>$this->description,
            'competences'=>$this->competences,
            'date_limite'=>$this->date_limite,
            'budget'=>$this->budget
        ]);
    }

    // 🔹 Afficher
    static function afficher() {
        $db = config::getConnexion();
        return $db->query("SELECT * FROM offres ORDER BY id DESC")->fetchAll();
    }

    // 🔹 Supprimer
    static function supprimer($id) {
        $db = config::getConnexion();
        $req = $db->prepare("DELETE FROM offres WHERE id=:id");
        $req->execute(['id'=>$id]);
    }

    // 🔹 Récupérer
    static function recuperer($id) {
        $db = config::getConnexion();
        $req = $db->prepare("SELECT * FROM offres WHERE id=:id");
        $req->execute(['id'=>$id]);
        return $req->fetch();
    }

    public function getEtat() {
    $today = date('Y-m-d');

    if ($today > $this->date_limite) {
        return "Fermée";
    } else {
        return "Active";
    }
}

    // 🔹 Modifier
    static function modifier($offre, $id) {
        $db = config::getConnexion();

        $sql = "UPDATE offres SET 
                titre=:titre,
                description=:description,
                competences=:competences,
                date_limite=:date_limite,
                budget=:budget
                WHERE id=:id";

        $req = $db->prepare($sql);
        $req->execute([
            'id'=>$id,
            'titre'=>$offre->titre,
            'description'=>$offre->description,
            'competences'=>$offre->competences,
            'date_limite'=>$offre->date_limite,
            'budget'=>$offre->budget
        ]);
    }
}
?>