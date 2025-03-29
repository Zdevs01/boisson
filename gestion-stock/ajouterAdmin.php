<?php


if (isset($_POST['ajouter'])) {
    require_once("php/Class/Admin.php");

    extract($_POST);
    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "./image/admin/" . $filename;
    $role = $_POST['role'] ?? 'Gestionnaire'; // Par défaut, Gestionnaire si non défini

    // Vérification de l'upload de l'image
    if (!move_uploaded_file($tempname, $folder)) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => '❌ Échec du téléchargement de l\'image !'];
        header("Location: newuser.php");
        exit();
    }

    // Création de l'admin
    $nv_admin = new Admin($nom, $prenom, $adr, $tele, $email, $folder, $mdp);
    if ($nv_admin->AjouterAdmin()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => '✅ Admin ajouté avec succès !'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => '❌ Erreur lors de l\'ajout de l\'admin !'];
    }
    

    header("Location: newuser.php");
    exit();
}
?>
