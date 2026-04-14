<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/Offre.php';

class OffreController {

    // 🔹 afficher les offres
    public function listOffres() {
        $sql = "SELECT * FROM offres";
        $db = config::getConnexion();
        return $db->query($sql);
    }

    // 🔹 ajouter une offre
    public function addOffre($offre) {
        $sql = "INSERT INTO offres (titre, description) VALUES (?, ?)";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            $offre->getTitre(),
            $offre->getDescription()
        ]);
    }

    // 🔹 supprimer
    public function deleteOffre($id) {
    $sql = "DELETE FROM offres WHERE id = ?";
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    $query->execute([$id]);
}

    // 🔹 récupérer une offre
    public function getOffre($id) {
        $sql = "SELECT * FROM offres WHERE id = ?";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([$id]);
        return $query->fetch();
    }

    // 🔹 modifier
   public function updateOffre($offre) {
    $sql = "UPDATE offres SET titre = ?, description = ? WHERE id = ?";
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    $query->execute([
        $offre->getTitre(),
        $offre->getDescription(),
        $offre->getId()
    ]);
}
}
?>