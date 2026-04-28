<?php
require_once __DIR__ . '/../Model/Candidature.php';

class CandidatureController {

    function afficherCandidatures($search = "") {
        if (!empty($search)) {
            return $this->rechercherCandidatures($search);
        }
        return Candidature::afficher();
    }

    function rechercherCandidatures($search) {
        $search = htmlspecialchars(trim($search));
        return Candidature::rechercher($search);
    }

    function ajouterCandidature($candidature) {
        $candidature->ajouter();
        return "success";
    }

    function supprimerCandidature($id) {
        return Candidature::supprimer($id);
    }

   function changerStatut($id, $statut) {

    $allowed = ['en_attente', 'validee', 'refusee', 'entretien']; // ✅ AJOUT

    if (!in_array($statut, $allowed)) {
        return "❌ Statut invalide";
    }

    Candidature::changerStatut($id, $statut);
    return "success";
}

    function planifierEntretien($id, $date, $heure) {

    if (empty($date) || empty($heure)) {
        throw new Exception("Champs invalides");
    }

    return Candidature::planifierEntretien($id, $date, $heure);
}
    function getStats() {
        return Candidature::getStats();
    }

    function countCandidatures() {
        return Candidature::countAll();
    }

    function countEnAttente() {
        return Candidature::countAttente();
    }

    function countValidees() {
        return Candidature::countValide();
    }

    function countRefusees() {
        return Candidature::countRefuse();
    }

    function countEntretiens() {
        return Candidature::countEntretien();
    }
}
?>