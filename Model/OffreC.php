<?php
include_once __DIR__ . '/config.php';

class OffreC {

    // 🔹 Afficher toutes les offres
    function afficherOffres() {
        $sql = "SELECT * FROM offres ORDER BY id DESC";
        $db = config::getConnexion();
        return $db->query($sql);
    }

    // 🔹 Ajouter une offre (CORRIGÉ)
    function ajouterOffre($titre, $description, $competences, $date_limite, $budget) {

        $sql = "INSERT INTO offres 
        (titre, description, competences, date_limite, budget) 
        VALUES 
        (:titre, :description, :competences, :date_limite, :budget)";
        
        $db = config::getConnexion();
        $req = $db->prepare($sql);

        $req->execute([
            'titre' => $titre,
            'description' => $description,
            'competences' => $competences,
            'date_limite' => $date_limite,
            'budget' => $budget
        ]);
    }

    // 🔹 Supprimer une offre
    function supprimerOffre($id) {
        $sql = "DELETE FROM offres WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->execute(['id' => $id]);
    }

    // 🔹 Récupérer une offre
    function recupererOffre($id) {
        $sql = "SELECT * FROM offres WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->execute(['id' => $id]);
        return $req->fetch(); // 🔥 important
    }

    // 🔹 Modifier une offre (CORRIGÉ)
    function modifierOffre($titre, $description, $competences, $date_limite, $budget, $id) {

        $sql = "UPDATE offres SET 
            titre = :titre,
            description = :description,
            competences = :competences,
            date_limite = :date_limite,
            budget = :budget
            WHERE id = :id";

        $db = config::getConnexion();
        $req = $db->prepare($sql);

        $req->execute([
            'id' => $id,
            'titre' => $titre,
            'description' => $description,
            'competences' => $competences,
            'date_limite' => $date_limite,
            'budget' => $budget
        ]);
    }
}
?>