<?php
session_start();
require_once("php/Class/Marque.php");

// Vérification de la session
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

$active = array(0, 0, "active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

if (isset($_POST['add'])) {
    extract($_POST);

    // Vérifier si une image a été téléchargée
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/brand/" . $filename; 

        if (!move_uploaded_file($tempname, $image)) {
            $_SESSION['message'] = ['type' => 'error', 'content' => 'Échec du téléchargement de l\'image.'];
            header("Location: addbrand.php");
            exit();
        }
    } else {
        // Si aucune image n'est téléchargée, on définit une image par défaut
        $image = "./image/brand/default.jpg";
    }

    try {
        $brand = new Marque($nom_marque, $description_marque, $image);
        $brand->inserMarque();

        $_SESSION['message'] = ['type' => 'success', 'content' => 'Marque ajoutée avec succès.'];
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'content' => 'Erreur lors de l\'ajout de la marque.'];
    }

    // Redirection après l'opération
    header("Location: brandlist.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Template Admin Bootstrap" />
  <meta name="keywords"
    content="admin, gestion, bootstrap, entreprise, entrepôt, boissons, créatif, facture, html5, responsive, projets" />
  <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Ajouter un Type de boisson 🍾</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
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
            <h4>Ajouter un Type de boisson 🍹</h4>
            <h6>Enregistrez un nouveau Type de boisson 🆕</h6>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="addbrand.php" enctype="multipart/form-data">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom du Type de boisson 🍾</label>
                  <input type="text" name="nom_marque" required />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Description 📝</label>
                  <textarea class="form-control" name="description_marque" required></textarea>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Image du produit 📷 (facultatif)</label>
                  <div class="image-upload">
                    <input type="file" name="image" accept="image/png, image/jpeg" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez-déposez un fichier pour télécharger 📂</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" type="submit" name="add">Ajouter ✅</button>
                <a href="brandlist.php" class="btn btn-cancel" type="reset">Annuler ❌</a>
              </div>
            </form>
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
