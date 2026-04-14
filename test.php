<?php
$host = "localhost";
$dbname = "recrutement_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Connexion réussie 🎉";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>