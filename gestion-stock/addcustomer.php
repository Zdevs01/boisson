<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Client.php");

  // Activation du menu correspondant
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0, 0);

  if (isset($_POST['submit'])) {
    extract($_POST);

    // Vérification et gestion de l'image
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/client/" . $filename;

        if (!move_uploaded_file($tempname, $image)) {
            $_SESSION['message'] = ['type' => 'error', 'content' => '❌ Échec du téléchargement de l\'image du client.'];
            header("Location: addcustomer.php");
            exit();
        }
    } else {
        // Image par défaut si aucune n'est fournie
        $image = "./image/client/default.png";
    }

    try {
        // Création du client
        $client = new Client($nom, $prenom, $adr, $tele, $email, $image);
        $client->Ajouter("client");

        $_SESSION['message'] = ['type' => 'success', 'content' => '✅ Client ajouté avec succès ! 🎉'];
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'content' => '⚠️ Une erreur est survenue lors de l\'ajout du client.'];
    }

    // Redirection après l'opération
    header("Location: customerlist.php");
    exit();
  }
?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Gestion d'Entrepôt de Boissons" />
  <meta name="keywords" content="admin, gestion, entrepôt, boissons, stocks, ventes" />
  <meta name="author" content="Dreamguys - Gestion d'Entrepôt" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Ajouter un Client 🍾📦</title>

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
      .col-lg-3 { width: 33%; }
      .col-lg-9 { width: 67%; }
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
            <h4>Ajouter un Client 🏪</h4>
            <h6>Enregistrez un nouveau client pour l'entrepôt</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="addcustomer.php" enctype="multipart/form-data">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom du Client 🧑‍💼</label>
                  <input type="text" name="prenom" placeholder="Ex : Dupont" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Prénom du Client</label>
                  <input type="text" name="nom" placeholder="Ex : Jean" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>E-mail 📧</label>
                  <input type="text" name="email" placeholder="exemple@mail.com" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Téléphone 📞</label>
                  <input type="text" name="tele" placeholder="+237 6XX XXX XXX" />
                </div>
              </div>
              <div class="col-lg-9 col-12">
                <div class="form-group">
                  <label>Adresse 📍</label>
                  <input type="text" name="adr" placeholder="Ex : Rue des Brasseurs, Douala" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Photo de Profil 🖼️</label>
                  <div class="image-upload">
                    <input type="file" name="image" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez-déposez un fichier pour télécharger</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" name="submit">Ajouter ✅</button>
                <a href="customerlist.php" class="btn btn-cancel">Annuler ❌</a>
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
</body>

</html>
