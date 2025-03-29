<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Supplier.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0);

  if (isset($_POST['submit'])) {
    extract($_POST);

    // Vérifier si une image a été téléchargée
    if ($_FILES["image"]["name"]) {
      $filename = $_FILES["image"]["name"];
      $tempname = $_FILES["image"]["tmp_name"];
      $image = "./image/supplier/" . $filename;

      // Essayer de déplacer l'image téléchargée
      if (move_uploaded_file($tempname, $image)) {
        // Image téléchargée avec succès
        $Supplier = new Supplier($nom, $prenom, $adr, $tele, $email, $image);
        $Supplier->Ajouter("fournisseur");
      } else {
        exit("<h3>Échec du téléchargement de l'image!</h3>");
      }
    } else {
      // Si aucune image n'a été téléchargée, utiliser une image par défaut
      $image = "./image/supplier/default.png"; // Assurez-vous que l'image par défaut existe

      $Supplier = new Supplier($nom, $prenom, $adr, $tele, $email, $image);
      $Supplier->Ajouter("fournisseur");
    }

     // Redirection après l'opération
     header("Location: supplierlist.php");
     exit();
  }
?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Gestion des Fournisseurs" />
  <meta name="keywords" content="gestion, fournisseur, entreprise, logistique, stock, approvisionnement" />
  <meta name="author" content="Entrepôt de Boissons" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Ajouter un Fournisseur 📦</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    @media (min-width: 992px) {
      .col-lg-3 {
        flex: 0 0 auto;
        width: 33%;
      }
      .col-lg-9 {
        flex: 0 0 auto;
        width: 67%;
      }
    }
  </style>
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
            <h4>Ajouter un Fournisseur 📦</h4>
            <h6>Enregistrer un nouveau fournisseur dans le système 🏭</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="addsupplier.php" enctype="multipart/form-data">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom du Fournisseur 👤</label>
                  <input type="text" name="nom" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Prénom du Fournisseur 👤</label>
                  <input type="text" name="prenom" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>E-mail ✉️</label>
                  <input type="email" name="email" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Téléphone 📞</label>
                  <input type="text" name="tele" />
                </div>
              </div>
              <div class="col-lg-9 col-12">
                <div class="form-group">
                  <label>Adresse 🏠</label>
                  <input type="text" name="adr" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label> Avatar 📸</label>
                  <div class="image-upload">
                    <input type="file" name="image" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez-déposez un fichier pour téléverser 📤</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" name="submit">Ajouter ✅</button>
                <a href="supplierlist.php" class="btn btn-cancel">Annuler ❌</a>
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
