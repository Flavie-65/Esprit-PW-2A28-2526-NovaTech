<?php
$host = "localhost";
$dbname = "recrutement_db";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    $sql = "INSERT INTO offres (titre, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$titre, $description]);

    echo "Offre ajoutée ✅";
}
?>

<h2>Ajouter une offre</h2>

<form method="POST">
    <input type="text" name="titre" placeholder="Titre"><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <button type="submit">Ajouter</button>
</form>