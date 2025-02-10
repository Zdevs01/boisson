<?php
if(isset($_POST['id'])){
    $sale_id = $_POST['id'];
    $conn = new mysqli("localhost", "root", "", "boisson");

    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Suppression de l'enregistrement de vente
    $qry = $conn->query("DELETE FROM sales_list WHERE id = $sale_id");
    
    if($qry) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Échec de la suppression']);
    }

    $conn->close();
}
