﻿<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Supplier.php");
  require_once("php/Class/Product.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0);
  if (isset($_GET['id_sup'])) {
    $id = $_GET['id_sup'];
    Supplier::supprimer($id, "fournisseur");
  }
  $suppliers = Supplier::afficher("fournisseur");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion d'Entrepôt de Boissons" />
  <meta name="keywords" content="entrepôt, boissons, gestion, stock, fournisseurs" />
  <meta name="author" content="Dreamguys - Template Admin" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Liste des Fournisseurs 🍾📦</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />
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
            <h4>Liste des Fournisseurs 🍾</h4>
            <h6>Gérez vos fournisseurs efficacement 📦</h6>
          </div>
          <div class="page-btn">
            <a href="addsupplier.php" class="btn btn-added">
              <img src="assets/img/icons/plus.svg" alt="ajouter" /> Ajouter un Fournisseur
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-top">
              <div class="search-set">
                <div class="search-input">
                  <a class="btn btn-searchset">
                    <img src="assets/img/icons/search-white.svg" alt="rechercher" />
                  </a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Nom du Fournisseur 🏢</th>
                    <th>Adresse 📍</th>
                    <th>Téléphone 📞</th>
                    <th>Email 📧</th>
                    <th>Action ⚙️</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($suppliers as $supplier): ?>
                  <tr>
                    <td class="productimgname">
                      <a href="javascript:void(0);" class="product-img">
                        <img src="<?= $supplier['image'] ?>" alt="fournisseur" />
                      </a>
                      <a href="javascript:void(0);">
                        <?= $supplier['nom'] . " " . $supplier['prenom']; ?>
                      </a>
                    </td>
                    <td><?= $supplier['adr'] ?></td>
                    <td><?= $supplier['tele'] ?></td>
                    <td><?= $supplier['email'] ?></td>
                    <td>
                      <a class="me-3" href="editsupplier.php?id_sup=<?= $supplier['id'] ?>">
                        <img src="assets/img/icons/edit.svg" alt="modifier" />
                      </a>
                      <a class="me-3" href="supplierlist.php?id_sup=<?= $supplier['id'] ?>">
                        <img src="assets/img/icons/delete.svg" alt="supprimer" />
                      </a>
                    </td>
                  </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script data-cfasync="false" src../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
  <script src="assets/js/jquery-3.6.0.min.js"></script>

  <script src="assets/js/feather.min.js"></script>

  <script src="assets/js/jquery.slimscroll.min.js"></script>

  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>

  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <script src="assets/plugins/select2/js/select2.min.js"></script>

  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

  <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
  <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

  <script src="assets/js/script.js"></script>
</body>

</html>
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>