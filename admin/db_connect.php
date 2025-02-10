<?php
$servername = "localhost"; // Changez selon votre configuration
$username = "root"; // Changez selon votre configuration
$password = ""; // Changez selon votre configuration
$dbname = "boisson"; // Changez selon votre configuration

// Connexion à MySQL avec PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
