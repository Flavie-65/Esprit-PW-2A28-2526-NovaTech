<?php
require_once __DIR__ . '/../Model/Candidature.php';
require_once __DIR__ . '/../Model/config.php';

class CandidatureController {

    private function db() {
        return config::getConnexion();
    }

    /* ================= AFFICHAGE ================= */

    public function afficherCandidatures($search = "") {
        $db = $this->db();

        return !empty($search)
            ? Candidature::search($db, trim($search))
            : Candidature::getAll($db);
    }

    /* 🔍 RECHERCHE (AJOUT IMPORTANT) */
    public function rechercherCandidatures($search) {
        $db = $this->db();
        return Candidature::search($db, trim($search));
    }

    /* ================= AJOUT ================= */

    public function ajouterCandidature($data) {
        $db = $this->db();

        $nom = trim($data['nom'] ?? '');
        $email = trim($data['email'] ?? '');
        $offre_id = (int)($data['offre_id'] ?? 0);

        if (!$nom || !$email) {
            throw new Exception("Champs obligatoires");
        }

        
        // 🔥 LIMITE 10


        if (Candidature::countByOffre($db, $offre_id) >= 10) {
            throw new Exception("Offre complète (10 max)");
        }

        // 🔥 DOUBLON
        if (Candidature::existeDeja($db, $email, $offre_id)) {
            throw new Exception("Déjà postulé");
        }

        return Candidature::insert($db, [
            'nom' => $nom,
            'email' => $email,
            'message' => trim($data['message'] ?? ""),
            'cv' => $data['cv'] ?? "",
            'statut' => 'en_attente',
            'offre_id' => $offre_id
        ]);
    }

    /* ================= SUPPRESSION ================= */

    public function supprimerCandidature($id) {
        $db = $this->db();

        if (!$id) throw new Exception("ID invalide");

        $c = Candidature::getById($db, $id);
        if (!$c) throw new Exception("Introuvable");

        return Candidature::deleteById($db, $id);
    }

    /* ================= STATUT ================= */

    public function changerStatut($id, $statut) {
        $db = $this->db();

        $allowed = ['en_attente','entretien','validee','refusee'];

        if (!in_array($statut, $allowed)) {
            throw new Exception("Statut invalide");
        }

        return Candidature::updateStatut($db, $id, $statut);
    }

    /* ================= ENTRETIEN ================= */

    public function planifierEntretien($id, $date, $heure) {
        $db = $this->db();

        if (!$date || !$heure) {
            throw new Exception("Date/heure requises");
        }

        if (Candidature::entretienExiste($db, $date, $heure)) {
            throw new Exception("Créneau déjà pris");
        }

        return Candidature::planifier($db, $id, $date, $heure);
    }

    /* ================= STATS ================= */

    public function getStats() {
        return Candidature::getStats($this->db());
    }

    public function countCandidatures() {
        return Candidature::countAll($this->db());
    }

    public function countEnAttente() {
        return Candidature::countAttente($this->db());
    }

    public function countValidees() {
        return Candidature::countValide($this->db());
    }

    public function countRefusees() {
        return Candidature::countRefuse($this->db());
    }

    public function countEntretiens() {
        return Candidature::countEntretien($this->db());
    }
}
?>