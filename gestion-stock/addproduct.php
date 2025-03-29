<?php
session_start();
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once("php/Class/Product.php");
  require_once("php/Class/Categorie.php");
  require_once("php/Class/Marque.php");

  // Récupération des catégories et marques disponibles
  $cats = Categorie::afficher("categorie");
  $brands = Marque::afficher("marque");

  // Activation du menu correspondant
  $active = array(0, 0, 0, 0, 0, 0, "active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

  if (isset($_POST['add'])) {
    extract($_POST);

    // Vérification et gestion de l'image
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/product/" . $filename; 

        if (!move_uploaded_file($tempname, $image)) {
            $_SESSION['message'] = ['type' => 'error', 'content' => '❌ Échec du téléchargement de l\'image du produit.'];
            header("Location: addproduct.php");
            exit();
        }
    } else {
        // Image par défaut si aucune n'est fournie
        $image = "./image/product/default.png";
    }

    try {
        // Création du produit
        $nv_pr = new Product($num_pr, $id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $image);
        $nv_pr->addPr();

        $_SESSION['message'] = ['type' => 'success', 'content' => '✅ Produit ajouté avec succès ! 🍾'];
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'content' => '⚠️ Une erreur est survenue lors de l\'ajout du produit.'];
    }

    // Redirection après l'opération
    header("Location:productlist.php");
    exit();
  }
?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="Gestion d'entrepôt de boissons" />
  <meta name="keywords" content="boissons, entrepôt, gestion, stock, administration" />
  <meta name="author" content="Dreamguys - Gestion Entrepôt" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Ajouter une Boisson 🍾</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

  <link rel="stylesheet" href="assets/css/animate.css" />

  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />

  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

  <link rel="stylesheet" href="assets/css/style.css" />

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
            <h4>Ajouter une Boisson 🍷</h4>
            <h6>Remplissez les informations pour enregistrer un nouveau produit</h6>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="addproduct.php" enctype="multipart/form-data">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Référence 📌</label>
                  <input type="text" name="num_pr" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Nom de la Boisson 🍾</label>
                  <input type="text" name="lib_pr" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Catégorie 🍹</label>
                  <select class="select" name="id_cat">
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($cats as $item): ?>
                    <option value="<?= $item['id_cat']; ?>">
                      <?= $item['lib_cat']; ?>
                    </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Type de Produit 🏷️</label>
                  <select class="select" name="id_marque">
                    <option value="">Choisir une Type de Produit</option>
                    <?php foreach ($brands as $item): ?>
                    <option value="<?= $item['id_marque']; ?>">
                      <?= $item['nom_marque']; ?>
                    </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Quantité en stock 📦</label>
                  <input type="text" name="qte_stock" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Prix d'achat 💰</label>
                  <input type="text" name="prix_achat" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Prix de vente 🏷️</label>
                  <input type="text" name="prix_uni" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Description 📝</label>
                  <input type="text" name="desc_pr" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Image du Produit 📸</label>
                  <div class="image-upload">
                    <input type="file" name="image" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Glissez-déposez un fichier ou cliquez pour importer</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" name="add">Ajouter la Boisson 🍺</button>
                <a href="productlist.php" class="btn btn-cancel">Annuler ❌</a>
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
