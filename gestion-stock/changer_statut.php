<?php
require_once("config.php");

if (isset($_POST['id']) && isset($_POST['statut'])) {
    $id = intval($_POST['id']);
    $nouveauStatut = ($_POST['statut'] == 1) ? 0 : 1; // Inversion du statut

    $sql = "UPDATE admin SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['statut' => $nouveauStatut, 'id' => $id]);

    echo "Statut mis à jour avec succès !";
} else {
    echo "Erreur lors de la mise à jour du statut.";
}
?>
