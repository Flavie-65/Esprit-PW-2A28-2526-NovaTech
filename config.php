<?php
class config {
    public static function getConnexion() {
        try {
            $conn = new PDO("mysql:host=localhost;dbname=recrutement_db", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>