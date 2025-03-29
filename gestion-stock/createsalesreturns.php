<?php
session_start();
require_once("php/Class/Product.php");
require_once("php/Class/Marque.php");
require_once("php/Class/Categorie.php");
require_once("php/Class/Client.php");
require_once("php/Class/Sale.php");
require_once("php/Class/PrSale.php");
require_once("config.php"); // Connexion à la base de données

// Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

$active = array(0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0, 0, 0, 0, 0, 0);

// Traitement du formulaire après soumission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    extract($_POST);

    // Calcul du montant total de la vente
    $tva_amount = ($prix_vente * $tva) / 100;
    $total_vente = $prix_vente + $tva_amount;

    // Démarrer la transaction
    $pdo->beginTransaction();

    try {
        // Insérer la vente dans la table `ventes`
        $stmt = $pdo->prepare("INSERT INTO ventes (id_client, date_vente, montant_paye, mode_paiement, solde, mode_vente, tva_active, tva_amount, total_vente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Utilisation de bindValue pour lier les valeurs
        $stmt->bindValue(1, $id_cli, PDO::PARAM_INT);
        $stmt->bindValue(2, $date_com, PDO::PARAM_STR);
        $stmt->bindValue(3, $montant_paye, PDO::PARAM_STR);
        $stmt->bindValue(4, $mode_paiement, PDO::PARAM_STR);
        $stmt->bindValue(5, $solde, PDO::PARAM_STR);
        $stmt->bindValue(6, $mode_vente, PDO::PARAM_STR);
        $stmt->bindValue(7, $tva, PDO::PARAM_INT);
        $stmt->bindValue(8, $tva_amount, PDO::PARAM_STR);
        $stmt->bindValue(9, $total_vente, PDO::PARAM_STR);
        
        // Exécution de la requête
        $stmt->execute();

        // Récupérer l'ID de la vente nouvellement insérée
        $id_vente = $pdo->lastInsertId();

        // Vérifier la disponibilité du produit
        $qty = Product::qtePr($num_pr);
        if ($qte_pr < $qty['qte_stock']) {
            if (!sale::isSale($num_com)) {
                $purchase = new Sale($num_com, $date_com, $id_cli);
                $purchase->add();
            }

            // Insérer le produit dans la table `ventes_produits`
            $product_of_sale = new PrSale($num_pr, $num_com, $qte_pr, $prix_vente);
            $product_of_sale->add();
            Product::deleteQty($num_pr, $qte_pr);

            // Commit de la transaction
            $pdo->commit();
            header("Location: sales.php?success=true");
            exit();
        } else {
            $out_of_stock = true;
        }
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollback();
        $error = "Une erreur est survenue, veuillez réessayer.";
    }
}

// Affichage des produits, marques, catégories et clients
$clients = Client::afficher("client");
$products = Product::afficher("produit");
$categories = Categorie::afficher("categorie");
$brands = Marque::afficher("marque");

// Suppression d'un produit de la vente
if (isset($_GET['num_pr'])) {
    extract($_GET);
    PrSale::deletePrSale($num_pr);
    $prsSales = PrSale::displayPrsSale($num_com);
    $sale = Sale::displaySale($num_com);
}

// Affichage des ventes
if (isset($_GET['num_com'])) {
    extract($_GET);
    $prsSales = PrSale::displayPrsSale($num_com);
    $sale = Sale::displaySale($num_com);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion des ventes - Entrepôt de boissons" />
  <meta name="keywords" content="boissons, entrepôt, gestion, ventes, administration" />
  <meta name="author" content="Dreamguys - Gestion Entrepôt" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Nouvelle Vente 🍾</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />

  <link rel="stylesheet" href="assets/css/animate.css" />

  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />

  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

  <link rel="stylesheet" href="assets/css/style.css" />

  <?php if (isset($request_payload)): ?>
  <style>

  </style>
  <?php endif ?>
</head>

<body>
  <div class="main-wrapper">
    <?php require_once("header.php"); ?>
    <?php require_once("sidebar.php"); ?>
    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Nouvelle Vente 🍺</h4>
            <h6>Enregistrer une nouvelle vente</h6>
          </div>
        </div>
        <div class="card">
           
        <form class="card-body" method="post" action="createsalesreturns.php">
    <div class="row">
        <!-- Nom du client -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>🧑‍💼 Nom du Client</label>
                <div class="row">
                    <div class="col-lg-10 col-sm-10 col-10">
                        <select class="select" name="id_cli">
                            <option>🛒 Sélectionner un client</option>
                            <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id']; ?>" 
                                <?php if (!empty($sale) && $client['id'] === $sale['id_cli']) echo "selected"; ?>>
                                <?= $client['nom'] . " " . $client['prenom']; ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-2 ps-0">
                        <div class="add-icon">
                            <a href="./addcustomer.php"><img src="assets/img/icons/plus1.svg" alt="Ajouter client" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marque -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>🏷️ Type de Produit</label>
                <div class="row">
                    <div class="col-lg-10 col-sm-10 col-10">
                        <select class="select" name="id_marque" id="brand">
                            <option value="">🔖 Sélectionner un type de produit</option>
                            <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id_marque']; ?>"><?= $brand['nom_marque']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-2 ps-0">
                        <div class="add-icon">
                            <a href="./addbrand.php"><img src="assets/img/icons/plus1.svg" alt="Ajouter un type de produit" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catégorie -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>📦 Catégorie</label>
                <div class="row">
                    <div class="col-lg-10 col-sm-10 col-10">
                        <select class="select" name="id_cat" id="cat">
                            <option value="">📂 Sélectionner une catégorie</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_cat']; ?>"><?= $cat['lib_cat']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-2 ps-0">
                        <div class="add-icon">
                            <a href="./addcategory.php"><img src="assets/img/icons/plus1.svg" alt="Ajouter catégorie" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produit -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>🍾 Nom du Produit</label>
                <div class="row">
                    <div class="col-lg-10 col-sm-10 col-10">
                        <select class="select" name="num_pr">
                            <option>📜 Sélectionner un produit</option>
                            <?php foreach ($products as $pr): ?>
                            <option value="<?= $pr['num_pr']; ?>"><?= $pr['lib_pr']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-2 ps-0">
                        <div class="add-icon">
                            <a href="./addproduct.php"><img src="assets/img/icons/plus1.svg" alt="Ajouter produit" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Type de produit (Carton ou Pack) -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>🔄 Type de Vente</label>
                <select class="select" name="type_vente" required>
                    <option value="">Sélectionner un type</option>
                    <option value="carton">Carton</option>
                    <option value="pack">Pack</option>
                </select>
            </div>
        </div>

        <!-- Quantité -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>🔢 Quantité</label>
                <input type="number" name="qte_pr" required min="1" />
                <?php if (isset($out_of_stock)): ?>
                <p style="color:red; text-align: center">⚠️ Stock insuffisant</p>
                <?php endif ?>
            </div>
        </div>

        <!-- Prix appliqué (tarif spécifique client) -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>💰 Prix (€)</label>
                <input type="text" name="prix_vente" required placeholder="Ex: 50.00" />
            </div>
        </div>

        <!-- TVA -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>📊 TVA (en %)</label>
                <input type="number" name="tva" min="0" step="0.1" placeholder="Ex: 20" />
            </div>
        </div>

        <!-- Montant payé -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>💳 Montant payé</label>
                <input type="text" name="montant_paye" required placeholder="Ex: 50.00" />
            </div>
        </div>

        <!-- Mode de paiement -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>💳 Mode de paiement</label>
                <select class="select" name="mode_paiement" required>
                    <option value="">Sélectionner le mode de paiement</option>
                    <option value="cash">Espèces</option>
                    <option value="credit">Crédit</option>
                </select>
            </div>
        </div>

        <!-- Solde éventuel -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>💸 Solde</label>
                <input type="text" name="solde" placeholder="Solde restant (si applicable)" />
            </div>
        </div>

        <!-- Mode de vente (en entrepôt ou livraison) -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>🚚 Mode de vente</label>
                <select class="select" name="mode_vente" required>
                    <option value="">Sélectionner un mode</option>
                    <option value="entrepot">Vente en entrepôt (retrait sur place)</option>
                    <option value="livraison">Livraison</option>
                </select>
            </div>
        </div>

        <!-- Date de vente -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>📅 Date de Vente</label>
                <div class="input-groupicon">
                    <input type="text" placeholder="JJ-MM-AAAA" class="datetimepicker" name="date_com" value="<?= !empty($sale) ? $sale['date_com'] : ''; ?>" required />
                    <div class="addonset">
                        <img src="assets/img/icons/calendars.svg" alt="Calendrier" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Référence -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label>📄 Référence</label>
                <input type="text" name="num_com" placeholder="Ex: REF123456" value="<?= !empty($sale) ? $sale['num_com'] : ''; ?>" required />
            </div>
        </div>

    </div>

    <!-- Bouton de soumission -->
    





    <!-- Tableau des produits ajoutés -->
    <div class="row">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>📦 Produit</th>
                    <th>💲 Prix (€)</th>
                    <th>🔢 Qté</th>
                    <th>🏷️ Type (Carton/Pack)</th>
                    <th>🔢 TVA</th>
                    <th>❌ Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($prsSales)): ?>
                <?php foreach ($prsSales as $pr): ?>
                <tr>
                    <td class="productimgname">
                        <a class="product-img">
                            <img src="<?= $pr['pr_image'] ?>" alt="Produit" />
                        </a>
                        <a href="javascript:void(0);"><?= $pr['lib_pr'] ?></a>
                    </td>
                    <td><?= number_format($pr['prix_vente'], 2, ',', ' ') ?> €</td>
                    <td><?= $pr['qte_pr'] ?></td>
                    <td><?= $pr['type_prod'] ?></td> <!-- Type (carton/pack) -->
                    <td>
                        <!-- Option TVA -->
                        <?php if ($pr['tva_active']): ?>
                            <?= number_format($pr['tva_amount'], 2, ',', ' ') ?> €
                        <?php else: ?>
                            Non applicable
                        <?php endif ?>
                    </td>
                    <td>
                        <a href="createsalesreturns.php?num_pr=<?= $pr['num_pr'] ?>&amp;num_com=<?= $sale['num_com'] ?>">
                            <img src="assets/img/icons/delete.svg" alt="Supprimer" />
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- Boutons d'action -->
    <div class="col-lg-12">
        <button class="btn btn-submit me-2" type="submit" name="add">✅ Ajouter</button>
        <a href="salesreturnlists.php" class="btn btn-cancel">❌ Annuler</a>
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
