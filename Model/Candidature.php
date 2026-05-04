<?php
require_once __DIR__ . '/config.php';

class Candidature {

    // 🔹 INSERT
    public static function insert($db, $data) {
        $sql = "INSERT INTO candidatures (nom,email,message,cv,statut,offre_id)
                VALUES (:nom,:email,:message,:cv,:statut,:offre_id)";

        $req = $db->prepare($sql);
        return $req->execute($data);
    }

    // 🔹 SELECT ALL
    public static function getAll($db) {
        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                ORDER BY c.id DESC";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 GET BY ID
    public static function getById($db, $id) {
        $req = $db->prepare("SELECT * FROM candidatures WHERE id = :id");
        $req->execute(['id' => (int)$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // 🔍 SEARCH
    public static function search($db, $search) {
        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                WHERE c.nom LIKE :s OR c.email LIKE :s
                ORDER BY c.id DESC";

        $req = $db->prepare($sql);
        $req->execute(['s' => "%$search%"]);

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 DELETE
    public static function deleteById($db, $id) {
        $req = $db->prepare("DELETE FROM candidatures WHERE id = :id");
        return $req->execute(['id' => (int)$id]);
    }

    // 🔹 UPDATE STATUT
    public static function updateStatut($db, $id, $statut) {
        $req = $db->prepare("UPDATE candidatures SET statut = :s WHERE id = :id");

        return $req->execute([
            's' => $statut,
            'id' => (int)$id
        ]);
    }

    // 🔹 PLANIFIER ENTRETIEN
    public static function planifier($db, $id, $date, $heure) {
        $sql = "UPDATE candidatures 
                SET date_entretien = :date,
                    heure_entretien = :heure,
                    statut = 'entretien'
                WHERE id = :id";

        $req = $db->prepare($sql);

        return $req->execute([
            'date' => $date,
            'heure' => $heure,
            'id' => (int)$id
        ]);
    }

    // 🔥 NOUVELLE FONCTION MÉTIER (IMPORTANT)
    public static function countByOffre($db, $offre_id) {
        $req = $db->prepare("SELECT COUNT(*) FROM candidatures WHERE offre_id = ?");
        $req->execute([(int)$offre_id]);
        return $req->fetchColumn();
    }

    // 🔥 STATS
    public static function getStats($db) {
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
    public static function countAll($db) {
        return $db->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();
    }

    public static function countAttente($db) {
        return $db->query("SELECT COUNT(*) FROM candidatures WHERE statut='en_attente'")->fetchColumn();
    }

    public static function countValide($db) {
        return $db->query("SELECT COUNT(*) FROM candidatures WHERE statut='validee'")->fetchColumn();
    }

    public static function countRefuse($db) {
        return $db->query("SELECT COUNT(*) FROM candidatures WHERE statut='refusee'")->fetchColumn();
    }

    public static function countEntretien($db) {
        return $db->query("SELECT COUNT(*) FROM candidatures WHERE statut='entretien'")->fetchColumn();
    }
}
?>