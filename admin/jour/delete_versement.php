<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sms_db', 'root', '');

// Vérifier si l'ID est fourni
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Supprimer la ligne avec l'ID correspondant
    $stmt = $pdo->prepare("DELETE FROM versement_journalier WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucun ID fourni.']);
}
?>
