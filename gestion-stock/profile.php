<?php
session_start();
require_once("php/Class/Admin.php");
include('config.php');  // Connexion à la base de données
$admin = $_SESSION['admin'] ?? null; // Vérifie si la session existe

// Génération d'un token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
  
    $password = $_POST['mdp'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email AND statut = 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mdp'])) {
        $_SESSION['admin_info'] = [
            'id' => $user['id'] ?? '',
            'nom' => $user['nom'] ?? '',
            'prenom' => $user['prenom'] ?? '',
            'email' => $user['email'] ?? '',
            'tele' => $user['tele'] ?? '',
            'adr' => $user['adr'] ?? '',
            'image' => !empty($user['image']) ? $user['image'] : 'assets/img/avatar-1.png',
        ];

        $_SESSION['message'] = ['type' => 'success', 'content' => 'Connexion réussie !'];
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'content' => 'Email ou mot de passe incorrect.'];
    }
}

if (isset($_SESSION['admin_info'])) {
    require_once("php/Class/Admin.php");
    $admins = Admin::afficher("admin");

    if (isset($_POST['submit_data'])) {
        extract($_POST);
        Admin::modifierAdmin($id, $nom, $prenom, $adr, $tele, $email, $mdp, "admin");
        $_SESSION['admin_info'] = Admin::estAdmin($nom,$email, $mdp);
    }

    if (isset($_POST['submit_image']) && !empty($_FILES["image"]["name"])) {
        extract($_POST);
        $filename = basename($_FILES["image"]["name"]);
        $tempname = $_FILES["image"]["tmp_name"];
        $new_image = "./image/admin/" . $filename;
        $old_image = $_SESSION['admin_info']['image'] ?? '';

        if (move_uploaded_file($tempname, $new_image)) {
            Admin::modifierImageAdmin($id, $new_image);
            if (!empty($old_image) && file_exists($old_image) && $old_image !== 'assets/img/avatar-1.png') {
                unlink($old_image);
            }
            $_SESSION['admin_info']['image'] = $new_image;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Entrepôt de Boissons - Template Admin Bootstrap">
    <meta name="keywords" content="admin, gestion, entrepôt, boissons, entreprise, créatif, inventaire, html5, responsive, projets">
    <meta name="author" content="Dreamguys - Template Admin Bootstrap">
    <meta name="robots" content="noindex, nofollow">
    <title>Mon Profil</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
        }
        
        .profile-top {
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }
        
        .profile-content {
            display: flex;
            align-items: center;
        }
        
        .profile-contentimg img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3c3c3c;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .profileupload input[type="file"] {
            display: none;
        }
        
        .profileupload a img {
            cursor: pointer;
            width: 32px;
            height: 32px;
            margin-top: 10px;
        }

        .profile-contentname h2 {
            color: #333;
            font-size: 24px;
            margin-top: 10px;
        }
        
        .profile-contentname h4 {
            color: #888;
            font-size: 14px;
        }

        .form-group label {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }

        .btn-submit {
            background-color: #28a745;
            color: #fff;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .btn-cancel {
            background-color: #f44336;
            color: #fff;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-cancel:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        <?php require_once("header.php"); ?>
        <?php require_once("sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Mon Profil 🍻</h4>
                        <h6>Profil Administrateur 🛠️</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form class="profile-set" method="post" action="profile.php" enctype="multipart/form-data">
                            <div class="profile-head"></div>
                            <div class="profile-top">
                                <div class="profile-content">
                                    <div class="profile-contentimg">
                                        <?php if (!empty($admin['image'])) : ?>
                                            <img src="<?= htmlspecialchars($admin['image']) ?>" alt="img" id="blah">
                                        <?php else : ?>
                                            <img src="assets/img/avatar-1.png" alt="default" id="blah">
                                        <?php endif; ?>
                                        <div class="profileupload">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id'] ?? '') ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <input type="file" id="imgInp" name="image">
                                            <a href="javascript:void(0);">
                                                <img src="assets/img/icons/edit-set.svg" alt="Modifier">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="profile-contentname">
                                        <h2><?= htmlspecialchars($admin['nom'] ?? '') . " " . htmlspecialchars($admin['nom'] ?? '') ?></h2>
                                        <h4>Met à jour ta photo et tes informations personnelles 📸</h4>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <button class="btn btn-submit me-2" type="submit" name="submit_image">Mettre à jour</button>
                                    <button class="btn btn-cancel" type="reset" onclick="cancelPhoto()">Annuler</button>
                                </div>
                            </div>
                        </form>

                        <form class="row" method="post" action="profile.php">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id'] ?? '') ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Prénom 🧑‍💼</label>
                                    <input type="text" placeholder="William" value="<?= htmlspecialchars($admin['prenom'] ?? '') ?>" name="prenom">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nom 👨‍💼</label>
                                    <input type="text" placeholder="Castilo" value="<?= htmlspecialchars($admin['nom'] ?? '') ?>" name="nom">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email 📧</label>
                                    <input type="email" placeholder="william@example.com" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" name="email">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Mot de Passe 🔒</label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-input" name="mdp" placeholder="Nouveau mot de passe">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Téléphone 📞</label>
                                    <input type="text" placeholder="+212 766032618" value="<?= htmlspecialchars($admin['tele'] ?? '') ?>" name="tele">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Adresse 🏠</label>
                                    <input type="text" placeholder="Robert Robertson, 1234 NW Bobcat Lane" value="<?= htmlspecialchars($admin['adr'] ?? '') ?>" name="adr">
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-submit me-2" type="submit" name="submit_data">Mettre à jour</button>
                                <button class="btn btn-cancel" type="reset">Annuler</button>
                            </div>
                        </form>

                        <script>
                            function cancelPhoto() {
                                window.location.reload();
                            }
                        </script>
                    </div>
                </div>

            </div>
        </div>
    </div>




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
