<?php
require_once __DIR__ . '/../Model/Offre.php';
require_once __DIR__ . '/../Model/config.php';

class OffreController {

    // 🔹 AFFICHER
    function afficherOffres() {
        $db = config::getConnexion();
        return Offre::getAll($db);
    }

    // 🔹 AJOUTER
    function ajouterOffre($data) {
        $db = config::getConnexion();

        // ✅ validation (MVC correct)
        if (empty($data['titre']) || empty($data['description'])) {
            return "❌ Champs obligatoires";
        }

        return Offre::insert($db, [
            'titre' => trim($data['titre']),
            'description' => trim($data['description']),
            'competences' => trim($data['competences'] ?? ""),
            'date_limite' => $data['date_limite'],
            'budget' => $data['budget']
        ]);
    }

    // 🔹 SUPPRIMER
    function supprimerOffre($id) {
        $db = config::getConnexion();

        $offre = Offre::getById($db, $id);
        if (!$offre) {
            return "❌ Offre introuvable";
        }

        return Offre::deleteById($db, $id);
    }

    // 🔹 RÉCUPÉRER
    function recupererOffre($id) {
        $db = config::getConnexion();
        return Offre::getById($db, $id);
    }

    // 🔹 MODIFIER
    function modifierOffre($id, $data) {
        $db = config::getConnexion();

        if (empty($data['titre']) || empty($data['description'])) {
            return "❌ Champs obligatoires";
        }

        return Offre::update($db, $id, [
            'titre' => trim($data['titre']),
            'description' => trim($data['description']),
            'competences' => trim($data['competences'] ?? ""),
            'date_limite' => $data['date_limite'],
            'budget' => $data['budget']
        ]);
    }

    // 🔹 ÉTAT (logique métier ici ✔)
    function getEtat($date_limite) {
        $today = date('Y-m-d');
        return ($today > $date_limite) ? "Fermée" : "Active";
    }
}
?>