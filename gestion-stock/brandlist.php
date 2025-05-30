<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Marque.php");
  $active = array(0, "active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  if (isset($_GET['id_marque'])) {
    extract($_GET);
    Marque::suppMarque($id_marque);
  }
  $brands = Marque::afficher("marque");
?>
<!DOCTYPE html> 
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion de l'entrepôt de boissons" />
  <meta name="keywords"
    content="entrepôt, boissons, gestion, stock, administration, ventes, produits" />
  <meta name="author" content="Dreamguys - Gestion Entrepôt" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Liste des Marques 🍾</title>

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

  <?php require_once("header.php"); ?>
  <?php require_once("sidebar.php"); ?>

  <div class="main-wrapper">
    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>📜 Liste des Type de boissons</h4>
            <h6>Gérez vos fournisseurs et Type de boissons 🍷🍺🥤</h6>
          </div>
          <div class="page-btn">
            <a href="addbrand.php" class="btn btn-added"><img src="assets/img/icons/plus.svg" class="me-2"
                alt="img" /> Ajouter un Type de boissons</a>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-top">
              <div class="search-set">
                <div class="search-input">
                  <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img" /></a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Logo 🏷️</th>
                    <th>Nom du Type de boissons 🏆</th>
                    <th>Description 📜</th>
                    <th>Actions ⚙️</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < sizeof($brands); $i++): ?>
                  <tr>
                    <td>
                      <a class="product-img">
                        <img src="<?= $brands[$i]['br_image'] ?>" alt="marque" />
                      </a>
                    </td>
                    <td>
                      <?= $brands[$i]['nom_marque'] ?>
                    </td>
                    <td>
                      <?= $brands[$i]['description_marque'] ?>
                    </td>
                    <td>
                      <a class="me-3" href="editbrand.php?id_marque=<?= $brands[$i]['id_marque'] ?>">
                        <img src="assets/img/icons/edit.svg" alt="Modifier" title="Modifier" />
                      </a>
                      <a class="me-3" href="brandlist.php?id_marque=<?= $brands[$i]['id_marque'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Type de boissons ?')">
                        <img src="assets/img/icons/delete.svg" alt="Supprimer" title="Supprimer" />
                      </a>
                    </td>
                  </tr>
                  <?php endfor ?>
                </tbody>
              </table>
            </div>
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
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>