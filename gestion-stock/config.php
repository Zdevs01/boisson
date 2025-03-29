
<?php
$host = "localhost"; // Serveur MySQL (modifie si nécessaire)
$dbname = "gestion_des_stocks"; // Remplace par le nom de ta base
$username = "root"; // Par défaut sur XAMPP, l'utilisateur est "root"
$password = ""; // Sur XAMPP, il n'y a pas de mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
if (!$pdo) {
    die("Échec de la connexion à la base de données");
}

?>
