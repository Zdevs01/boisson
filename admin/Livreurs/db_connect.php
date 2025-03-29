<?php
$host = "localhost"; // Adresse du serveur MySQL (remplace si nécessaire)
$user = "root"; // Nom d'utilisateur MySQL (modifie si besoin)
$password = ""; // Mot de passe MySQL (ajoute si nécessaire)
$database = "boisson"; // Remplace par le nom de ta base de données

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Définir l'encodage des caractères en UTF-8
$conn->set_charset("utf8");

?>
