<?php
require_once __DIR__ . '/config.php';

class Candidature {

    private $nom;
    private $email;
    private $message;
    private $cv;
    private $statut;
    private $offre_id;

    function __construct($nom, $email, $message, $cv, $offre_id) {
        $this->nom = htmlspecialchars(trim($nom));
        $this->email = htmlspecialchars(trim($email));
        $this->message = htmlspecialchars(trim($message));
        $this->cv = $cv;
        $this->offre_id = (int)$offre_id;
        $this->statut = "en_attente";
    }

    // 🔹 AJOUTER
    public function ajouter() {
        $db = config::getConnexion();

        $sql = "INSERT INTO candidatures (nom,email,message,cv,statut,offre_id)
                VALUES (:nom,:email,:message,:cv,:statut,:offre_id)";

        $req = $db->prepare($sql);

        return $req->execute([
            'nom' => $this->nom,
            'email' => $this->email,
            'message' => $this->message,
            'cv' => $this->cv,
            'statut' => $this->statut,
            'offre_id' => $this->offre_id
        ]);
    }

    // 🔹 AFFICHER
    public static function afficher() {
        $db = config::getConnexion();

        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                ORDER BY c.id DESC";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔍 RECHERCHE
    public static function rechercher($search) {
        $db = config::getConnexion();

        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                WHERE c.nom LIKE :s OR c.email LIKE :s
                ORDER BY c.id DESC";

        $req = $db->prepare($sql);
        $req->execute(['s' => "%$search%"]);

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 SUPPRIMER
    public static function supprimer($id) {
        $db = config::getConnexion();

        $req = $db->prepare("DELETE FROM candidatures WHERE id = :id");
        return $req->execute(['id' => (int)$id]);
    }

    // 🔹 CHANGER STATUT
    public static function changerStatut($id, $statut) {

        $allowed = ['en_attente', 'validee', 'refusee', 'entretien'];

        if (!in_array($statut, $allowed)) {
            throw new Exception("Statut invalide");
        }

        $db = config::getConnexion();

        $req = $db->prepare("UPDATE candidatures SET statut = :s WHERE id = :id");

        return $req->execute([
            's' => $statut,
            'id' => (int)$id
        ]);
    }

    // 📅 PLANIFIER ENTRETIEN (VERSION CORRIGÉE + PRO)
    // 📅 PLANIFIER / MODIFIER ENTRETIEN (VERSION FINALE)
public static function planifierEntretien($id, $date, $heure)
{
    $db = config::getConnexion();

    // 🔍 Vérifier si la candidature existe
    $check = $db->prepare("SELECT statut FROM candidatures WHERE id = :id");
    $check->execute(['id' => (int)$id]);
    $c = $check->fetch(PDO::FETCH_ASSOC);

    if (!$c) {
        throw new Exception("Candidature introuvable");
    }

    // ✅ Autoriser VALIDE + ENTRETIEN
    if (!in_array($c['statut'], ['validee', 'entretien'])) {
        throw new Exception("La candidature doit être validée");
    }

    // ⏰ Vérifier date future
    $dateTime = strtotime($date . ' ' . $heure);
    if ($dateTime <= time()) {
        throw new Exception("Date invalide");
    }

    // 🔥 UPDATE (planifier OU modifier)
    $sql = "UPDATE candidatures 
            SET date_entretien = :date,
                heure_entretien = :heure,
                statut = 'entretien'
            WHERE id = :id";

    $req = $db->prepare($sql);

    if (!$req->execute([
        'date' => $date,
        'heure' => $heure,
        'id' => (int)$id
    ])) {
        throw new Exception("Erreur lors de la mise à jour");
    }

    return true;
}

    // 🔥 STATS
    public static function getStats() {
        $db = config::getConnexion();

        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(statut='validee') as valide,
                    SUM(statut='refusee') as refuse,
                    SUM(statut='en_attente') as attente,
                    SUM(statut='entretien') as entretien
                FROM candidatures";

        $res = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        $total = $res['total'] ?: 1;

        return [
            'total'      => $res['total'],
            'valide'     => round(($res['valide'] / $total) * 100, 2),
            'refuse'     => round(($res['refuse'] / $total) * 100, 2),
            'attente'    => round(($res['attente'] / $total) * 100, 2),
            'entretien'  => round(($res['entretien'] / $total) * 100, 2)
        ];
    }

    // 🔹 DASHBOARD
    public static function countAll() {
        return config::getConnexion()
            ->query("SELECT COUNT(*) FROM candidatures")
            ->fetchColumn();
    }

    public static function countAttente() {
        return config::getConnexion()
            ->query("SELECT COUNT(*) FROM candidatures WHERE statut='en_attente'")
            ->fetchColumn();
    }

    public static function countValide() {
        return config::getConnexion()
            ->query("SELECT COUNT(*) FROM candidatures WHERE statut='validee'")
            ->fetchColumn();
    }

    public static function countRefuse() {
        return config::getConnexion()
            ->query("SELECT COUNT(*) FROM candidatures WHERE statut='refusee'")
            ->fetchColumn();
    }

    public static function countEntretien() {
        return config::getConnexion()
            ->query("SELECT COUNT(*) FROM candidatures WHERE statut='entretien'")
            ->fetchColumn();
    }
}
?>