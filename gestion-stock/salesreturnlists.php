<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Sale.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0, 0, 0, 0, 0, 0, 0);
  if (isset($_GET["num_com"])) {
    extract($_GET);
    Sale::deleteSale($num_com);
  }
  $sales = Sale::displayAllSales();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion des ventes - Entrepôt de boissons" />
  <meta name="keywords" content="entrepôt, boissons, gestion, ventes, facturation, stocks" />
  <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>📋 Liste des ventes</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />

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
            <h4>📋 Liste des ventes</h4>
            <h6>📊 Suivez et gérez vos ventes</h6>
          </div>
          <div class="page-btn">
            <a href="createsalesreturns.php" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img" class="me-2" />➕ Nouvelle vente</a>
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
                    <th>🔖 Référence</th>
                    <th>👤 Client</th>
                    <th>📅 Date</th>
                    <th>💰 Total (XAF)</th>
                    <th>⚙️ Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($sales as $sale): ?>
                  <tr>
                    <td><?= $sale['num_com'] ?></td>
                    <td class="productimgname">
                      <a href="javascript:void(0);" class="product-img">
                        <img src="<?= $sale['image'] ?>" alt="client" />
                      </a>
                      <a href="javascript:void(0);">👤 <?= $sale['nom'] . " " . $sale['prenom'] ?></a>
                    </td>
                    <td>📅 <?= $sale['date_com'] ?></td>
                    <td>💵 <?= $sale['total'] ?></td>
                    <td>
                      <a class="me-3" href="sale-details.php?num_com=<?= $sale['num_com'] ?>">
                        <img src="assets/img/icons/eye.svg" alt="Détails" />
                      </a>
                      <a target="_blank" title="Télécharger PDF" href="printPdf.php?num_com=<?= $sale['num_com'] ?>">
                        <img src="assets/img/icons/pdf.svg" alt="Télécharger" />
                      </a>
                      <a href="salesreturnlists.php?num_com=<?= $sale['num_com'] ?>" class="me-3">
                        <img src="assets/img/icons/delete.svg" alt="Supprimer" />
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

  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

  <script src="assets/plugins/select2/js/select2.min.js"></script>

  <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
  <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

  <script src="assets/js/script.js"></script>
</body>

</html>
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>