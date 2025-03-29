<?php
include 'db_connect.php'; // Connexion à la base de données

if(isset($_POST['id'])){
    $id = intval($_POST['id']); // Récupérer l'ID passé par AJAX

    // Vérifier si la commande existe dans la base de données
    $qry = $conn->query("SELECT status FROM sales_list WHERE id = '$id'");
    if($qry->num_rows > 0){
        $row = $qry->fetch_assoc();
        $new_status = ($row['status'] == 1) ? 0 : 1; // Inverser le statut

        // Mise à jour du statut dans la base de données
        $update = $conn->query("UPDATE sales_list SET status = '$new_status' WHERE id = '$id'");

        // Vérifier si la mise à jour s'est bien effectuée
        if($update){
            echo "success:$new_status"; // Répondre avec le nouveau statut
        } else {
            echo "Erreur SQL: " . $conn->error; // Retourner une erreur si la mise à jour échoue
        }
    } else {
        echo "Erreur: Commande introuvable"; // Si la commande n'est pas trouvée
    }
} else {
    echo "Erreur: ID manquant"; // Si l'ID n'est pas passé
}
?>
