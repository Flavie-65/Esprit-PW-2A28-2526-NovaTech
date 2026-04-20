<?php
include_once __DIR__ . '/../Model/CandidatureC.php';

class CandidatureController {

    function afficherCandidatures() {
        $candidatureC = new CandidatureC();
        return $candidatureC->afficherCandidatures();
    }

    function ajouterCandidature($candidature) {
        $candidatureC = new CandidatureC();
        $candidatureC->ajouterCandidature($candidature);
    }

    function supprimerCandidature($id) {
        $candidatureC = new CandidatureC();
        $candidatureC->supprimerCandidature($id);
    }

    function changerStatut($id, $statut) {
        $candidatureC = new CandidatureC();
        $candidatureC->changerStatut($id, $statut);
    }
    function countCandidatures() {
    $cC = new CandidatureC();
    return $cC->countCandidatures();
}

function countEnAttente() {
    $cC = new CandidatureC();
    return $cC->countEnAttente();
}

function countValidees() {
    $cC = new CandidatureC();
    return $cC->countValidees();
}
}
?>