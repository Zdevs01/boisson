<?php
session_start();
require_once("config.php"); // Connexion à la base de données

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

$active = array_fill(0, 16, 0);
$active[15] = "active";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajoute'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $adr = trim($_POST['adr']);
    $tele = trim($_POST['tele']);
    $email = trim($_POST['email']);
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Hachage du mot de passe
    $role = $_POST['role'];
    $image = "";

    // Vérification si l'email existe déjà
    $checkEmail = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
    $checkEmail->execute([$email]);

    if ($checkEmail->rowCount() > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Erreur ❌', 'Cet e-mail est déjà utilisé', 'error');
            });
        </script>";
    } else {
        // Gestion de l’image
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "image/admin/";
            $image = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $image;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];

            if (!in_array($imageFileType, $allowedTypes)) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire('Erreur ❌', 'Format d’image invalide ! (JPG, JPEG, PNG uniquement)', 'error');
                    });
                </script>";
            } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire('Erreur ❌', 'Échec du téléchargement de l’image', 'error');
                    });
                </script>";
            }
        }

        // Insertion des données dans la base
        $sql = "INSERT INTO admin (nom, prenom, adr, tele, email, image, mdp, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nom, $prenom, $adr, $tele, $email, $image, $mdp, $role])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Succès ✅',
                        text: 'Administrateur ajouté avec succès !',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => { window.location = 'userlists.php'; });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire('Erreur ❌', 'Problème lors de l\'ajout', 'error');
                });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Template Admin Bootstrap">
    <meta name="keywords" content="admin, gestion, bootstrap, entreprise, facture, html5, responsive, projet">
    <meta name="author" content="Dreamguys - Template Admin Bootstrap">
    <meta name="robots" content="noindex, nofollow">
    <title>Nouvel Administrateur 👨‍💼</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
        <?php require_once("header.php"); ?>
        <?php require_once("sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Nouvel Administrateur 👤</h4>
                        <h6>Créer un nouvel administrateur 🆕</h6>
                    </div>
                </div>
                  <!-- Zone d'affichage des messages -->
                  <div id="alert-message"></div>

                <form class="card" method="post" action="" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Nom de famille 👨‍👩‍👧‍👦</label>
                                    <input type="text" name="nom">
                                </div>
                                <div class="form-group">
                                    <label>Adresse 📍</label>
                                    <input type="text" name="adr">
                                </div>
                                <div class="form-group">
                                    <label>E-mail 📧</label>
                                    <div class="form-addons">
                                        <input type="text" name="email" value="">
                                    </div>
                                </div>

                                <div class="form-group">
    <label>Role 📧</label>
    <div class="form-addons">
        <select name="role" class="form-control">
            <option value="Administrateur">Administrateur</option>
            <option value="Gestionnaire">Gestionnaire</option>
            <option value="Livreur">Livreur</option>
            <option value="Chauffeur">Chauffeur</option>
            <option value="Caissière">Caissière</option>
        </select>
    </div>
</div>


                                
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Prénom 👤</label>
                                    <input type="text" name="prenom">
                                </div>
                                <div class="form-group">
                                    <label>Téléphone 📞</label>
                                    <input type="text" name="tele">
                                </div>
                                <div class="form-group">
                                    <label>Mot de passe 🔑</label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-inputs" name="mdp" value="">
                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="image">Photo de profil 📷</label>
                                    <div class="image-upload image-upload-new">
                                        <input type="file" id="image" name="image" accept="image/png, image/jpeg">
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Glissez-déposez un fichier à télécharger 📂</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2" type="submit" name="ajoute">Ajouter ✅</button>
                                <button class="btn btn-cancel" type="reset">Annuler ❌</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

 <script src="assets/js/bootstrap.bundle.min.js"></script>


    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>
