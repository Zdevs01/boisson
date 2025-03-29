<?php
session_start();

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, redirige-le vers la page de connexion
    header('Location: signin.php');
    exit();
}

// Le reste de ton code pour afficher le tableau de bord
require_once("php/Class/Client.php");
require_once("php/Class/Supplier.php");
require_once("php/Class/Purchase.php");
require_once("php/Class/Sale.php");
require_once("php/Class/Product.php");
$active = array("active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$clients = Client::nbrDesTuples("client");
$suppliers = Supplier::nbrDesTuples("fournisseur");
$purchases = Purchase::TotalLigne("approvisionnement");
$sales = Sale::TotalLigne("commande");
$products = Product::afficher("produit");
$almost_expired_products = Product::afficherExepiredPr();
$all_sales = Sale::topSales();
$all_purchases = Purchase::displayAllPur();
$total_all_sales = 0;
foreach ($all_sales as $item) {
    $total_all_sales += $item['total'];
}
$total_all_pur = 0;
foreach ($all_purchases as $value) {
    $total_all_pur += $value['total'];
}
$total_all_pr = 0;
foreach ($products as $value) {
    $total_all_pr += $value['qte_stock'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>AMITAM Store</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <div id=" global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        <?php require_once("header.php"); ?>
        <?php require_once("sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="content">
            <div class="row">
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget">
            <div class="dash-widgetimg">
                <span>💸</span>
            </div>
            <div class="dash-widgetcontent">
                <h5><span class="counters" data-count="<?= $total_all_pur ?>"><?= $total_all_pur ?>€</span></h5>
                <h6>🛒 Achat Total</h6>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget dash1">
            <div class="dash-widgetimg">
                <span>📈</span>
            </div>
            <div class="dash-widgetcontent">
                <h5><span class="counters" data-count="<?= $total_all_sales ?>"><?= $total_all_sales ?>€</span></h5>
                <h6>💰 Ventes Totales</h6>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget dash2">
            <div class="dash-widgetimg">
                <span>📊</span>
            </div>
            <div class="dash-widgetcontent">
                <h5><span class="counters" data-count="<?= $total_all_sales - $total_all_pur ?>">
                    <?= $total_all_sales - $total_all_pur ?>€
                </span></h5>
                <h6>🏆 Bénéfice Total</h6>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget dash3">
            <div class="dash-widgetimg">
                <span>📦</span>
            </div>
            <div class="dash-widgetcontent">
                <h5><span class="counters" data-count="<?= $total_all_pr ?>"><?= $total_all_pr ?> 📦</span></h5>
                <h6>📦 Total des Produits</h6>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count">
            <div class="dash-counts">
                <h4>👥 <?= $clients ?></h4>
                <h5>Clients</h5>
            </div>
            <div class="dash-imgs">
                <i data-feather="user"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das1">
            <div class="dash-counts">
                <h4>🤝 <?= $suppliers ?></h4>
                <h5>Fournisseurs</h5>
            </div>
            <div class="dash-imgs">
                <i data-feather="user-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das2">
            <div class="dash-counts">
                <h4>🧾 <?= $purchases ?></h4>
                <h5>Factures d'Achat</h5>
            </div>
            <div class="dash-imgs">
                <i data-feather="file-text"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das3">
            <div class="dash-counts">
                <h4>📜 <?= $sales ?></h4>
                <h5>Factures de Vente</h5>
            </div>
            <div class="dash-imgs">
                <i data-feather="file"></i>
            </div>
        </div>
    </div>
</div>




           
   
                <div class="row"> 
                <div class="col-lg-7 col-sm-12 col-12 d-flex">
    <div class="card flex-fill">
        <h4 class="card-title mb-0" style="padding:15px;">🏆 Top 4 Ventes</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>🔢 Référence Vente</th>
                    <th>👤 Client</th>
                    <th>📅 Date</th>
                    <th>💶 Total (€)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_ventes = count($all_sales);
                for ($i = 0; $i < min(4, $total_ventes); $i++):
                    if (!isset($all_sales[$i])) continue;
                ?>
                <tr>
                    <td><?= htmlspecialchars($all_sales[$i]['nom'] ?? 'N/A') ?></td>
                    <td class="productimgname">
                        <a href="javascript:void(0);" class="product-img">
                            <img src="<?= htmlspecialchars($all_sales[$i]['image'] ?? 'default.jpg') ?>" alt="produit" />
                        </a>
                        <a href="javascript:void(0);">
                            <?= htmlspecialchars(($all_sales[$i]['nom'] ?? '') . " " . ($all_sales[$i]['prenom'] ?? '')) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($all_sales[$i]['date_com'] ?? 'N/A') ?></td>
                    <td>💰 <?= htmlspecialchars($all_sales[$i]['total'] ?? '0') ?> €</td>
                </tr>
                <?php endfor ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-lg-5 col-sm-12 col-12 d-flex">
    <div class="card flex-fill">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">🆕 Produits récemment ajoutés</h4>
            <div class="dropdown">
                <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="dropset">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a href="productlist.php" class="dropdown-item">📋 Liste des Produits</a>
                    </li>
                    <li>
                        <a href="addproduct.php" class="dropdown-item">➕ Ajouter un Produit</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive dataview">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>🔢 N°</th>
                            <th>📦 Produits</th>
                            <th>💵 Prix (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_produits = count($products);
                        $j = 0;
                        for ($i = max(0, $total_produits - 4); $i < $total_produits; $i++):
                            $j++;
                        ?>
                        <tr>
                            <td><?= $j; ?></td>
                            <td class="productimgname">
                                <a href="productlist.php" class="product-img">
                                    <img src="<?= htmlspecialchars($products[$i]['pr_image'] ?? 'default.jpg') ?>" alt="produit">
                                </a>
                                <a href="productlist.php"> <?= htmlspecialchars($products[$i]['lib_pr'] ?? 'N/A') ?> </a>
                            </td>
                            <td>💶 <?= htmlspecialchars($products[$i]['prix_uni'] ?? '0') ?> €</td>
                        </tr>
                        <?php endfor ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>





<div class="card mb-0">
    <div class="card-body">
        <h4 class="card-title">⚠️ Stock Faible en Entrepôt</h4>
        <div class="table-responsive dataview">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>🔢 N°</th>
                        <th>📦 Nom du Produit</th>
                        <th>🏷️ Marque</th>
                        <th>🛒 Catégorie</th>
                        <th>💶 Prix d'Achat (€)</th>
                        <th>📉 Quantité Restante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_products = count($almost_expired_products);
                    for ($i = 0; $i < min(4, $total_products); $i++): 
                        if (!isset($almost_expired_products[$i])) continue;
                    ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td class="productimgname">
                            <a class="product-img" href="productlist.php">
                                <img src="<?= htmlspecialchars($almost_expired_products[$i]['pr_image'] ?? 'default.jpg') ?>" alt="produit">
                            </a>
                            <a href="productlist.php"><?= htmlspecialchars($almost_expired_products[$i]['lib_pr'] ?? 'N/A') ?></a>
                        </td>
                        <td><?= htmlspecialchars($almost_expired_products[$i]['nom_marque'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($almost_expired_products[$i]['lib_cat'] ?? 'N/A') ?></td>
                        <td>💶 <?= htmlspecialchars($almost_expired_products[$i]['prix_achat'] ?? '0') ?> €</td>
                        <td><?= htmlspecialchars($almost_expired_products[$i]['qte_stock'] ?? '0') ?></td>
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

    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>
