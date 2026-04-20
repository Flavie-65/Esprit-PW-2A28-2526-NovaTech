<?php
include_once __DIR__ . '/config.php';

class CandidatureC {

    // 🔹 Afficher toutes les candidatures (avec titre de l'offre)
    function afficherCandidatures() {
        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                ORDER BY c.date_candidature DESC";

        $db = config::getConnexion();
        return $db->query($sql)->fetchAll();
    }

    // 🔹 Ajouter une candidature
    function ajouterCandidature($c) {
        $sql = "INSERT INTO candidatures 
                (nom, email, cv, statut, offre_id)
                VALUES (:nom, :email, :cv, :statut, :offre_id)";

        $db = config::getConnexion();
        $req = $db->prepare($sql);

        $req->execute([
            'nom' => $c['nom'],
            'email' => $c['email'],
            'cv' => $c['cv'],
            'statut' => $c['statut'], // en_attente par défaut
            'offre_id' => $c['offre_id']
        ]);
    }

    // 🔹 Supprimer une candidature
    function supprimerCandidature($id) {
        $sql = "DELETE FROM candidatures WHERE id = :id";

        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->execute(['id' => $id]);
    }

    // 🔹 Récupérer une candidature
    function recupererCandidature($id) {
        $sql = "SELECT * FROM candidatures WHERE id = :id";

        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->execute(['id' => $id]);

        return $req->fetch();
    }

    // 🔹 Modifier une candidature
    function modifierCandidature($c, $id) {
        $sql = "UPDATE candidatures SET 
                    nom = :nom,
                    email = :email,
                    cv = :cv,
                    statut = :statut,
                    offre_id = :offre_id
                WHERE id = :id";

        $db = config::getConnexion();
        $req = $db->prepare($sql);

        $req->execute([
            'id' => $id,
            'nom' => $c['nom'],
            'email' => $c['email'],
            'cv' => $c['cv'],
            'statut' => $c['statut'],
            'offre_id' => $c['offre_id']
        ]);
    }

    // 🔹 Changer statut (valider / refuser)
    function changerStatut($id, $statut) {
        $sql = "UPDATE candidatures SET statut = :statut WHERE id = :id";

        $db = config::getConnexion();
        $req = $db->prepare($sql);

        $req->execute([
            'id' => $id,
            'statut' => $statut
        ]);
    }

    // 🔹 Compter les candidatures
    function countCandidatures() {
        $sql = "SELECT COUNT(*) as total FROM candidatures";
        $db = config::getConnexion();
        return $db->query($sql)->fetch()['total'];
    }

    function countEnAttente() {
        $sql = "SELECT COUNT(*) as total FROM candidatures WHERE statut = 'en_attente'";
        $db = config::getConnexion();
        return $db->query($sql)->fetch()['total'];
    }

    function countValidees() {
        $sql = "SELECT COUNT(*) as total FROM candidatures WHERE statut = 'validee'";
        $db = config::getConnexion();
        return $db->query($sql)->fetch()['total'];
    }

    function countRefusees() {
        $sql = "SELECT COUNT(*) as total FROM candidatures WHERE statut = 'refusee'";
        $db = config::getConnexion();
        return $db->query($sql)->fetch()['total'];
    }
}
?>