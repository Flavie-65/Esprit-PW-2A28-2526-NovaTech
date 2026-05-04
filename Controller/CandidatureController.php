<?php
require_once __DIR__ . '/../Model/Candidature.php';
require_once __DIR__ . '/../Model/config.php';

class CandidatureController {

    // 🔹 AFFICHER
    function afficherCandidatures($search = "") {
        $db = config::getConnexion();

        if (!empty($search)) {
            return $this->rechercherCandidatures($search);
        }

        return Candidature::getAll($db);
    }

    // 🔍 RECHERCHE
    function rechercherCandidatures($search) {
        $db = config::getConnexion();
        return Candidature::search($db, trim($search));
    }

    // 🔹 AJOUTER (🔥 AVEC LIMITE)
    function ajouterCandidature($data) {
        $db = config::getConnexion();

        if (empty($data['nom']) || empty($data['email'])) {
            return "❌ Champs obligatoires";
        }

        // 🔥 LIMITE MÉTIER (max 10)
        $max = 10;
        $count = Candidature::countByOffre($db, $data['offre_id']);

        if ($count >= $max) {
            return "❌ Offre complète (limite atteinte)";
        }

        return Candidature::insert($db, [
            'nom' => trim($data['nom']),
            'email' => trim($data['email']),
            'message' => trim($data['message'] ?? ""),
            'cv' => $data['cv'] ?? "",
            'statut' => 'en_attente',
            'offre_id' => (int)$data['offre_id']
        ]);
    }

    // 🔹 SUPPRIMER
    function supprimerCandidature($id) {
        $db = config::getConnexion();

        $c = Candidature::getById($db, $id);
        if (!$c) {
            return "❌ Candidature introuvable";
        }

        return Candidature::deleteById($db, $id);
    }

    // 🔹 CHANGER STATUT (🔥 WORKFLOW PRO)
    function changerStatut($id, $statut) {
        $db = config::getConnexion();

        $allowed = ['en_attente', 'validee', 'refusee', 'entretien'];

        if (!in_array($statut, $allowed)) {
            return "❌ Statut invalide";
        }

        $c = Candidature::getById($db, $id);
        if (!$c) {
            return "❌ Candidature introuvable";
        }

        // 🔥 WORKFLOW MÉTIER
        $transitions = [
            'en_attente' => ['entretien'],
            'entretien'  => ['validee', 'refusee'],
            'validee'    => [],
            'refusee'    => []
        ];

        $current = $c['statut'];

        if (!in_array($statut, $transitions[$current])) {
            return "❌ Transition interdite";
        }

        return Candidature::updateStatut($db, $id, $statut);
    }

    // 📅 PLANIFIER ENTRETIEN (🔥 VERSION PRO)
    function planifierEntretien($id, $date, $heure) {
        $db = config::getConnexion();

        if (empty($date) || empty($heure)) {
            return "❌ Champs invalides";
        }

        $c = Candidature::getById($db, $id);
        if (!$c) {
            return "❌ Candidature introuvable";
        }

        // 🔴 format
        if (!preg_match("/^\d{2}:\d{2}$/", $heure)) {
            return "❌ Format heure invalide";
        }

        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            return "❌ Format date invalide";
        }

        // 🔴 futur
        $dateTime = strtotime($date . ' ' . $heure);
        if ($dateTime <= time()) {
            return "❌ Date doit être future";
        }

        // 🔥 WEEKEND BLOQUÉ
        $day = date('N', strtotime($date));
        if ($day >= 6) {
            return "❌ Pas d'entretien le week-end";
        }

        // 🔥 CONFLIT ENTRETIEN
        if (Candidature::entretienExiste($db, $date, $heure)) {
            return "❌ Créneau déjà réservé";
        }

        return Candidature::planifier($db, $id, $date, $heure);
    }

    // 🔥 STATS
    function getStats() {
        $db = config::getConnexion();
        return Candidature::getStats($db);
    }

    // 🔹 DASHBOARD
    function countCandidatures() {
        $db = config::getConnexion();
        return Candidature::countAll($db);
    }

    function countEnAttente() {
        $db = config::getConnexion();
        return Candidature::countAttente($db);
    }

    function countValidees() {
        $db = config::getConnexion();
        return Candidature::countValide($db);
    }

    function countRefusees() {
        $db = config::getConnexion();
        return Candidature::countRefuse($db);
    }

    function countEntretiens() {
        $db = config::getConnexion();
        return Candidature::countEntretien($db);
    }
}
?>