<?php
session_start();
require_once("php/Class/Categorie.php");

// Vérification de la session
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_POST['edit'])) {
    extract($_POST);

    // Vérification si une nouvelle image a été téléchargée
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {
        // Pas de nouvelle image, on garde l'ancienne
        $image = $old_image;
    } else {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/category/" . $filename;

        // Suppression de l'ancienne image si elle existe et n'est pas l'image par défaut
        if (!empty($old_image) && file_exists($old_image) && $old_image !== "./image/category/default.jpg") {
            unlink($old_image);
        }

        // Upload de la nouvelle image
        if (!move_uploaded_file($tempname, $image)) {
            $_SESSION['message'] = ['type' => 'error', 'content' => '❌ Échec du téléchargement de l\'image.'];
            header("Location: editcategory.php?id_cat=" . $id_cat);
            exit();
        }
    }

    try {
        Categorie::modifierCat($id_cat, $lib_cat, $desc_cat, $image);
        $_SESSION['message'] = ['type' => 'success', 'content' => '✅ Catégorie mise à jour avec succès ! 🎉'];
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'content' => '⚠️ Erreur lors de la mise à jour de la catégorie.'];
    }

    // Redirection après modification
    header("Location: categorylist.php");
    exit();
}

// Vérification de l'ID de la catégorie pour récupérer ses données
if (isset($_GET['id_cat'])) {
    $cat = Categorie::affichetCat($_GET['id_cat']); // Vérifie si cette méthode existe bien
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Système de gestion d'entrepôt de boissons" />
  <meta name="keywords" content="entrepôt, boissons, gestion, stock, commerce" />
  <meta name="author" content="Dreamguys - Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Modifier une Catégorie 🍷📦</title>

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
            <h4>Modifier une Catégorie 🍾</h4>
            <h6>Mettre à jour une catégorie de boissons</h6>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="editcategory.php" enctype="multipart/form-data">
              <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom de la Catégorie 🏷️</label>
                  <input type="hidden" name="id_cat" value="<?= $cat['id_cat'] ?>" />
                  <input type="text" name="lib_cat" value="<?= $cat['lib_cat'] ?>" />
                </div>
              </div>
              <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                  <label>Description 📜</label>
                  <input type="text" name="desc_cat" value="<?= $cat['desc_cat'] ?>" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Image de la Catégorie 📸</label>
                  <div class="image-upload">
                    <input type="file" name="image" accept="image/png, image/jpeg" />
                    <input type="hidden" name="old_image" value="<?= $cat['cat_image'] ?>" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez-déposez une image ici ou cliquez pour télécharger 📂</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" type="submit" name="edit">Mettre à Jour ✅</button>
                <a href="categorylist.php" class="btn btn-cancel">Annuler ❌</a>
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
