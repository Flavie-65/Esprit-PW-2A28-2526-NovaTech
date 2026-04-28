<?php
include_once __DIR__ . '/../Model/Offre.php';

class OffreController {

    function afficherOffres() {
        return Offre::afficher();
    }

    function ajouterOffre($titre, $description, $competences, $date_limite, $budget) {

    // 🔥 créer objet Offre
    $offre = new Offre($titre, $description, $competences, $date_limite, $budget);

    // 🔥 appeler la méthode ajouter
    $offre->ajouter();
}

    function supprimerOffre($id) {
        Offre::supprimer($id);
    }

    function recupererOffre($id) {
        return Offre::recuperer($id);
    }

    function modifierOffre($offre, $id) {
        Offre::modifier($offre, $id);
    }
}
?>