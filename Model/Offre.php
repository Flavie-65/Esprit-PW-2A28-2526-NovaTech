<?php
require_once __DIR__ . '/config.php';

class Offre {

    // 🔹 INSERT
    public static function insert($db, $data) {
        $sql = "INSERT INTO offres (titre, description, competences, date_limite, budget)
                VALUES (:titre, :description, :competences, :date_limite, :budget)";

        $req = $db->prepare($sql);

        return $req->execute($data);
    }

    // 🔹 SELECT ALL
    public static function getAll($db) {
        return $db->query("SELECT * FROM offres ORDER BY id DESC")
                  ->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 DELETE
    public static function deleteById($db, $id) {
        $req = $db->prepare("DELETE FROM offres WHERE id = :id");
        return $req->execute(['id' => (int)$id]);
    }

    // 🔹 GET ONE
    public static function getById($db, $id) {
        $req = $db->prepare("SELECT * FROM offres WHERE id = :id");
        $req->execute(['id' => (int)$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 UPDATE
    public static function update($db, $id, $data) {
        $sql = "UPDATE offres SET 
                titre = :titre,
                description = :description,
                competences = :competences,
                date_limite = :date_limite,
                budget = :budget
                WHERE id = :id";

        $req = $db->prepare($sql);

        return $req->execute([
            'id' => (int)$id,
            'titre' => $data['titre'],
            'description' => $data['description'],
            'competences' => $data['competences'],
            'date_limite' => $data['date_limite'],
            'budget' => $data['budget']
        ]);
    }
}
?>