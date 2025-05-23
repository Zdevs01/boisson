<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Product.php");
  $active = array(0, 0, 0, 0, 0, "active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  if (isset($_GET['num_pr'])) {
    extract($_GET);
    Product::deletePr($num_pr);
  }
  $products = Product::prJoinCatJoinMarque();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion d'Entrepôt de Boissons - Admin" />
  <meta name="keywords" content="boissons, entrepôt, gestion, inventaire, commerce" />
  <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Liste des Produits 🍾📦</title>

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
            <h4>📋 Liste des Produits</h4>
            <h6>Gérez votre stock de boissons 🍻</h6>
          </div>
          <div class="page-btn">
            <a href="addproduct.php" class="btn btn-added">
              <img src="assets/img/icons/plus.svg" alt="img" class="me-1" />
              Ajouter un Nouveau Produit 🍹
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-top">
              <div class="search-set">
                <div class="search-input">
                  <a class="btn btn-searchset">
                    <img src="assets/img/icons/search-white.svg" alt="img" />
                  </a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Nom du Produit 🏷️</th>
                    <th>Référence 🔖</th>
                    <th>Catégorie 📂</th>
                    <th>Marque 🏭</th>
                    <th>Prix d'Achat 💰</th>
                    <th>Prix Unitaire 💶</th>
                    <th>Quantité 📦</th>
                    <th>Action 🛠️</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $pr): ?>
                  <tr>
                    <td class="productimgname">
                      <a href="javascript:void(0);" class="product-img">
                        <img src="<?= $pr['pr_image'] ?>" alt="produit" />
                      </a>
                      <a href="">
                        <?= $pr['lib_pr'] ?>
                      </a>
                    </td>
                    <td>
                      <?= $pr['num_pr'] ?>
                    </td>
                    <td>
                      <?= $pr['lib_cat'] ?>
                    </td>
                    <td>
                      <?= $pr['nom_marque'] ?>
                    </td>
                    <td>
                      <?= $pr['prix_achat'] ?>€
                    </td>
                    <td>
                      <?= $pr['prix_uni'] ?>€
                    </td>
                    <td>
                      <?= $pr['qte_stock'] ?>
                    </td>
                    <td>
                      <a class="me-3" href="product-details.php?num_pr=<?= $pr['num_pr']; ?>">
                        <img src="assets/img/icons/eye.svg" alt="voir" />
                      </a>
                      <a class="me-3" href="editproduct.php?num_pr=<?= $pr['num_pr']; ?>">
                        <img src="assets/img/icons/edit.svg" alt="modifier" />
                      </a>
                      <a href="productlist.php?num_pr=<?= $pr['num_pr']; ?>">
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