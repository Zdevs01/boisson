<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Supplier.php");
  require_once("php/Class/Product.php");
  require_once("php/Class/Purchase.php");
  require_once("php/Class/PrPurchase.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0, 0, 0, 0);
  if (isset($_POST['add'])) {
    extract($_POST);
    // ila deja 3amro inser had purchase ..
    if (!Purchase::isPurchase($num_app)) {
      $pruchase = new Purchase($num_app, $date_app, $id, $desc_app);
      $pruchase->add();
    }
    // echo ("<pre>");
    // print_r($_POST);
    $product_of_purchase = new PrPurchase($num_app, $num_pr, $qte_achete);
    try {
      $product_of_purchase->add();
    } catch (\Throwable $th) {
    }
    Product::editQty($num_pr, $qte_achete);
    $prPurchases = PrPurchase::displayPrPurchase($num_app);
    $pur = Purchase::displayPur($num_app);
    // print_r($pur);
  }
  if (isset($_GET['num_pr'])) {
    extract($_GET);
    Purchase::deletePrPurchase($num_pr, $num_app);
    $prPurchases = PrPurchase::displayPrPurchase($num_app);
    $pur = Purchase::displayPur($num_app);
  }

  if (isset($_GET['num_app'])) {
    extract($_GET);
    $prPurchases = PrPurchase::displayPrPurchase($num_app);
  }
  $suppliers = Supplier::afficher("fournisseur");
  $products = Product::afficher("produit");
  // print_r($prPurchases);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Bootstrap Admin Template" />
  <meta name="keywords"
    content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects" />
  <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Ajouter un Achat 🍻</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />

  <link rel="stylesheet" href="assets/css/animate.css" />

  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />

  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    @media (min-width: 992px) {
      .desc-form {
        flex: 0 0 auto;
        width: 75%;
      }
    }
  </style>

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
            <h4>Ajouter un Achat 🍹</h4>
            <h6>Enregistrer un nouvel achat de boissons</h6>
          </div>
        </div>
        <div class="card">
          <form class="card-body" method="post" action="addpurchase.php">
            <div class="row">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom du Fournisseur 💼</label>
                  <select class="select" name="id">
                    <option value="">Sélectionner</option>
                    <?php foreach ($suppliers as $item): ?>
                      <option value="<?= $item['id']; ?>">
                        <?= $item['nom'] . " " . $item['prenom']; ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Date d'Achat 📅</label>
                  <input type="text" placeholder="JJ-MM-AAAA" class="datetimepicker" name="date_app" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom du Produit 🍺</label>
                  <select class="select" name="num_pr">
                    <option value="">Choisir un produit</option>
                    <?php foreach ($products as $item): ?>
                      <option value="<?= $item['num_pr']; ?>">
                        <?= $item['lib_pr']; ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Référence 📂</label>
                  <input type="text" name="num_app" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Quantité 🍻</label>
                  <input type="text" name="qte_achete" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Description 📝</label>
                  <input type="text" class="form-control" name="desc_app" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Produit</th>
                      <th>Quantité</th>
                      <th>Prix d'Achat (€)</th>
                      <th class="text-end">Coût Unitaire (€)</th>
                      <th class="text-end">Coût Total (€)</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($prPurchases)): ?>
                    <?php foreach ($prPurchases as $item): ?>
                    <tr>
                      <td><?= $item['lib_pr'] ?></td>
                      <td><?= $item['qte_achete'] ?></td>
                      <td><?= $item['prix_achat'] ?> €</td>
                      <td class="text-end"><?= $item['prix_uni'] ?> €</td>
                      <td class="text-end"><?= $item['qte_achete'] * $item['prix_achat'] ?> €</td>
                      <td>
                        <a href="./addpurchase.php?num_pr=<?= $item['num_pr'] ?>&num_app=<?= $item['num_app'] ?>">
                          ❌
                        </a>
                      </td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-lg-12">
              <button class="btn btn-submit me-2" type="submit" name="add">Ajouter 👉</button>
              <a href="purchaselist.php" class="btn btn-cancel">Annuler ❌</a>
            </div>
          </form>
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