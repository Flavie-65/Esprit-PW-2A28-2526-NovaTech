<?php
require_once __DIR__ . '/config.php';

class Candidature {

    /* ================= CRUD ================= */

    public static function insert($db, $data) {
        $sql = "INSERT INTO candidatures (nom,email,message,cv,statut,offre_id)
                VALUES (:nom,:email,:message,:cv,:statut,:offre_id)";
        return $db->prepare($sql)->execute($data);
    }

    public static function getAll($db) {
        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                ORDER BY c.id DESC";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id) {
        $req = $db->prepare("SELECT * FROM candidatures WHERE id = ?");
        $req->execute([(int)$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /* 🔍 RECHERCHE AMÉLIORÉE */
    public static function search($db, $search) {

        $sql = "SELECT c.*, o.titre 
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                WHERE c.nom LIKE :s 
                   OR c.email LIKE :s
                   OR o.titre LIKE :s
                   OR c.statut LIKE :s
                ORDER BY c.id DESC";

        $req = $db->prepare($sql);
        $req->execute(['s' => "%$search%"]);

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteById($db, $id) {
        return $db->prepare("DELETE FROM candidatures WHERE id = ?")
                  ->execute([(int)$id]);
    }

    public static function updateStatut($db, $id, $statut) {
        return $db->prepare("UPDATE candidatures SET statut = ? WHERE id = ?")
                  ->execute([$statut, (int)$id]);
    }

    public static function planifier($db, $id, $date, $heure) {
        $sql = "UPDATE candidatures 
                SET date_entretien = ?, heure_entretien = ?, statut='entretien'
                WHERE id = ?";

        return $db->prepare($sql)->execute([$date, $heure, (int)$id]);
    }

    /* ================= MÉTIER ================= */

    public static function countByOffre($db, $offre_id) {
        $req = $db->prepare("SELECT COUNT(*) FROM candidatures WHERE offre_id=?");
        $req->execute([(int)$offre_id]);
        return $req->fetchColumn();
    }

    public static function entretienExiste($db, $date, $heure) {
        $req = $db->prepare("
            SELECT COUNT(*) FROM candidatures 
            WHERE date_entretien=? AND heure_entretien=?
        ");
        $req->execute([$date, $heure]);
        return $req->fetchColumn() > 0;
    }

    public static function existeDeja($db, $email, $offre_id) {
        $req = $db->prepare("
            SELECT COUNT(*) FROM candidatures 
            WHERE email=? AND offre_id=?
        ");
        $req->execute([$email, $offre_id]);
        return $req->fetchColumn() > 0;
    }

    public static function getByStatut($db, $statut) {
        $req = $db->prepare("SELECT * FROM candidatures WHERE statut=?");
        $req->execute([$statut]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= SCORING ================= */

    public static function calculerScore($candidat) {

    $score = 0;

    // 🔹 EXPÉRIENCE
    if ($candidat['experience'] >= 5) $score += 30;
    elseif ($candidat['experience'] >= 2) $score += 20;
    else $score += 10;

    // 🔹 NIVEAU
    switch ($candidat['niveau']) {
        case 'Ingénieur': $score += 20; break;
        case 'Master': $score += 15; break;
        case 'Licence': $score += 10; break;
    }

    // 🔹 CV
    if (!empty($candidat['cv'])) $score += 20;

    // 🔹 MESSAGE
    if (!empty($candidat['message'])) {
        if (strlen($candidat['message']) > 100) $score += 15;
        elseif (strlen($candidat['message']) > 50) $score += 10;
    }

    // 🔹 DOMAINE
    if (!empty($candidat['domaine'])) $score += 15;

    return min($score, 100);
}

    public static function getNiveauProfil($score) {

    if ($score >= 75) {
        return ['label' => 'Excellent', 'class' => 'success'];
    } elseif ($score >= 50) {
        return ['label' => 'Bon', 'class' => 'info'];
    } elseif ($score >= 30) {
        return ['label' => 'Moyen', 'class' => 'warning'];
    } else {
        return ['label' => 'Faible', 'class' => 'danger'];
    }
}

    public static function getPriorite($score) {

    if ($score >= 80) {
        return ['label' => '🔥 Urgent', 'class' => 'danger'];
    } elseif ($score >= 60) {
        return ['label' => '⭐ Important', 'class' => 'warning'];
    } elseif ($score >= 40) {
        return ['label' => '📌 Normal', 'class' => 'primary'];
    } else {
        return ['label' => '🧊 Faible', 'class' => 'secondary'];
    }
}

    /* ================= STATS ================= */

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
            'total'     => $res['total'],
            'valide'    => round(($res['valide'] / $total) * 100, 2),
            'refuse'    => round(($res['refuse'] / $total) * 100, 2),
            'attente'   => round(($res['attente'] / $total) * 100, 2),
            'entretien' => round(($res['entretien'] / $total) * 100, 2)
        ];
    }

    /* ================= DASHBOARD ================= */

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