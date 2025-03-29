<?php
session_start();
require_once("php/Class/Marque.php");

// Vérification de la session
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_POST['update'])) {
    extract($_POST);

    // Vérification si une nouvelle image a été téléchargée
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {
        // Pas de nouvelle image, on garde l'ancienne
        $image = $old_image;
    } else {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/brand/" . $filename;

        // Suppression de l'ancienne image si elle existe et n'est pas l'image par défaut
        if (!empty($old_image) && $old_image !== "./image/brand/default.jpg") {
            Marque::unlinkFile($old_image);
        }

        // Upload de la nouvelle image
        if (!move_uploaded_file($tempname, $image)) {
            $_SESSION['message'] = ['type' => 'error', 'content' => 'Échec du téléchargement de l\'image.'];
            header("Location: editbrand.php?id_marque=" . $id_marque);
            exit();
        }
    }

    try {
        Marque::modifierMarque($nom_marque, $description_marque, $image, $id_marque);
        $_SESSION['message'] = ['type' => 'success', 'content' => 'Marque mise à jour avec succès.'];
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'content' => 'Erreur lors de la mise à jour de la marque.'];
    }

    // Redirection après modification
    header("Location: brandlist.php");
    exit();
}

// Vérification de l'ID de la marque pour récupérer les données
if (isset($_GET['id_marque'])) {
    $brand = Marque::afficherMarque($_GET['id_marque']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion d'Entrepôt de Boissons - Interface Administrateur" />
  <meta name="keywords"
    content="admin, gestion, boissons, entrepôt, stock, marque, HTML5, responsive" />
  <meta name="author" content="Dreamguys - Interface Administrateur" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Modifier une Marque 🍷🍺</title>

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
            <h4>Modifier un Type de produit 🍷</h4>
            <h6>Mettre à jour les informations de votre marque</h6>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="editbrand.php" enctype="multipart/form-data">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom du type de produit</label>
                  <input type="hidden" name="id_marque" value="<?= $brand['id_marque'] ?>">
                  <input type="text" name="nom_marque" value="<?= $brand['nom_marque'] ?>" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Description</label>
                  <input type="text" class="form-control" name="description_marque"
                    value="<?= $brand['description_marque'] ?>">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Image du type de produit 📸</label>
                  <div class="image-upload">
                    <input type="file" name="image" accept="image/png, image/jpeg" value="<?= $brand['br_image'] ?>" />
                    <input type="hidden" name="old_image" value="<?= $brand['br_image'] ?>" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez-déposez un fichier ou cliquez pour télécharger 📂</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" type="submit" name="update">Mettre à Jour ✅</button>
                <a href="./brandlist.php" class="btn btn-cancel">Annuler ❌</a>
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
