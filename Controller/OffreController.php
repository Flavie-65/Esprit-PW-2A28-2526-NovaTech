<?php
include_once __DIR__ . '/../Model/OffreC.php';

class OffreController {

    function afficherOffres() {
        $sql = "SELECT * FROM offres";
    $db = config::getConnexion();
    $liste = $db->query($sql);

    return $liste->fetchAll();
    }

    function ajouterOffre($titre, $description, $competences, $date_limite, $budget) {
    $offreC = new OffreC();
    $offreC->ajouterOffre($titre, $description, $competences, $date_limite, $budget);
}
    function supprimerOffre($id) {
        $offreC = new OffreC();
        $offreC->supprimerOffre($id);
    }

    function modifierOffre($titre, $description, $competences, $date_limite, $budget, $id) {
    $offreC = new OffreC();
    $offreC->modifierOffre($titre, $description, $competences, $date_limite, $budget, $id);
}

    function recupererOffre($id) {
        $offreC = new OffreC();
        return $offreC->recupererOffre($id);
    }
}
?>