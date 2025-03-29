<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Categorie.php");

  // Activation du menu correspondant
  $active = array(0, 0, 0, 0, "active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

  if (isset($_POST['add'])) {
    extract($_POST);

    // Vérification et gestion de l'image
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/category/" . $filename;

        if (!move_uploaded_file($tempname, $image)) {
            $_SESSION['message'] = ['type' => 'error', 'content' => '❌ Échec du téléchargement de l\'image de la catégorie.'];
            header("Location: addcategory.php");
            exit();
        }
    } else {
        // Image par défaut si aucune n'est fournie
        $image = "./image/category/default.jpg";
    }

    try {
        // Création de la catégorie
        $cat = new Categorie($lib_cat, $desc_cat, $image);
        $cat->ajouterCat();

        $_SESSION['message'] = ['type' => 'success', 'content' => '✅ Catégorie ajoutée avec succès ! 🎉'];
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'content' => '⚠️ Une erreur est survenue lors de l\'ajout de la catégorie.'];
    }

    // Redirection après l'opération
    header("Location: categorylist.php");
    exit();
  }
?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion d'Entrepôt de Boissons 🍾📦" />
  <meta name="keywords" content="entrepôt, boissons, gestion, stock, administration" />
  <meta name="author" content="Dreamguys - Gestion Entrepôt" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Ajouter une Catégorie 🏷️</title>

  
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
            <h4>Ajouter une Catégorie 🏷️</h4>
            <h6>Créez une nouvelle catégorie de boissons 🍷🥤</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="addcategory.php" enctype="multipart/form-data">
              <div class="col-lg-6 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom de la Catégorie 📌</label>
                  <input type="text" name="lib_cat" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Description 📝</label>
                  <textarea class="form-control" name="desc_cat"></textarea>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label> Image de la Catégorie 📷</label>
                  <div class="image-upload">
                    <input type="file" name="image" accept="image/png, image/jpeg" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez et déposez un fichier pour téléverser 📂</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" type="submit" name="add">Ajouter 🆕</button>
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
