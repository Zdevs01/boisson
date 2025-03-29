<?php
session_start();
// print_r($_SESSION);
?>
<?php if (isset($_SESSION['admin'])): ?>
    <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Gestion d'Entrepôt de Boissons - Interface Admin">
    <meta name="keywords"
        content="admin, gestion, entrepôt, boissons, stocks, facturation, HTML5, responsive, projets">
    <meta name="author" content="Dreamguys - Template Admin">
    <meta name="robots" content="noindex, nofollow">
    <title>Gestion d'Entrepôt de Boissons 🍾📦</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        <div class="header">

            <div class="header-left active">
                <a href="index.html" class="logo">
                    <img src="assets/img/logo.png" alt="Logo Entrepôt">
                </a>
                <a href="index.html" class="logo-small">
                    <img src="assets/img/logo-small.png" alt="Logo">
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                </a>
            </div>

            <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>

            <ul class="nav user-menu">

                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="#">
                            <div class="searchinputs">
                                <input type="text" placeholder="Rechercher ici...">
                                <div class="search-addon">
                                    <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                </div>
                            </div>
                            <a class="btn" id="searchdiv"><img src="assets/img/icons/search.svg" alt="img"></a>
                        </form>
                    </div>
                </li>

                <li class="nav-item dropdown has-arrow flag-nav">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);"
                        role="button">
                        <img src="assets/img/flags/fr.png" alt="" height="20">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/fr.png" alt="" height="16"> Français
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/us.png" alt="" height="16"> Anglais
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/es.png" alt="" height="16"> Espagnol
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/de.png" alt="" height="16"> Allemand
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
        <img src="assets/img/icons/notification-bing.svg" alt="img"> 
        <span class="badge rounded-pill">4</span>
    </a>
    <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
            <span class="notification-title">🔔 Notifications</span>
            <a href="javascript:void(0)" class="clear-noti"> Effacer tout </a>
        </div>
        <div class="noti-content">
            <ul class="notification-list">
                <li class="notification-message">
                    <a href="activities.html">
                        <div class="media d-flex">
                            <span class="avatar flex-shrink-0">
                                <img alt="" src="assets/img/profiles/avatar-02.jpg">
                            </span>
                            <div class="media-body flex-grow-1">
                                <p class="noti-details"><span class="noti-title">Jean Dupont</span> a ajouté une nouvelle commande <span class="noti-title">📦 50 caisses de bières</span></p>
                                <p class="noti-time"><span class="notification-time">il y a 4 min</span></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="notification-message">
                    <a href="activities.html">
                        <div class="media d-flex">
                            <span class="avatar flex-shrink-0">
                                <img alt="" src="assets/img/profiles/avatar-03.jpg">
                            </span>
                            <div class="media-body flex-grow-1">
                                <p class="noti-details"><span class="noti-title">Sophie Martin</span> a modifié l'état d'une commande <span class="noti-title">🚚 Livraison en cours</span></p>
                                <p class="noti-time"><span class="notification-time">il y a 6 min</span></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="notification-message">
                    <a href="activities.html">
                        <div class="media d-flex">
                            <span class="avatar flex-shrink-0">
                                <img alt="" src="assets/img/profiles/avatar-06.jpg">
                            </span>
                            <div class="media-body flex-grow-1">
                                <p class="noti-details"><span class="noti-title">Michel Leroy</span> a ajouté <span class="noti-title">Julien Bernard</span> et <span class="noti-title">Claire Dubois</span> au projet <span class="noti-title">🛠️ Réorganisation du stock</span></p>
                                <p class="noti-time"><span class="notification-time">il y a 8 min</span></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="notification-message">
                    <a href="activities.html">
                        <div class="media d-flex">
                            <span class="avatar flex-shrink-0">
                                <img alt="" src="assets/img/profiles/avatar-17.jpg">
                            </span>
                            <div class="media-body flex-grow-1">
                                <p class="noti-details"><span class="noti-title">Paul Garnier</span> a terminé la tâche <span class="noti-title">📊 Mise à jour des stocks</span></p>
                                <p class="noti-time"><span class="notification-time">il y a 12 min</span></p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="notification-message">
                    <a href="activities.html">
                        <div class="media d-flex">
                            <span class="avatar flex-shrink-0">
                                <img alt="" src="assets/img/profiles/avatar-13.jpg">
                            </span>
                            <div class="media-body flex-grow-1">
                                <p class="noti-details"><span class="noti-title">Lucas Moreau</span> a ajouté une nouvelle tâche <span class="noti-title">📦 Vérification des livraisons</span></p>
                                <p class="noti-time"><span class="notification-time">il y a 2 jours</span></p>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="topnav-dropdown-footer">
            <a href="activities.html">Voir toutes les notifications</a>
        </div>
    </div>
</li>


<li class="nav-item dropdown has-arrow main-drop">
    <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
        <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
            <span class="status online"></span></span>
    </a>
    <div class="dropdown-menu menu-drop-user">
        <div class="profilename">
            <div class="profileset">
                <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
                    <span class="status online"></span></span>
                <div class="profilesets">
                    <h6>Jean Dupont</h6>
                    <h5>👨‍💼 Gérant</h5>
                </div>
            </div>
            <hr class="m-0">
            <a class="dropdown-item" href="profile.html"> <i class="me-2" data-feather="user"></i> Mon profil</a>
            <a class="dropdown-item" href="generalsettings.html"><i class="me-2"
                    data-feather="settings"></i> ⚙️ Paramètres</a>
            <hr class="m-0">
            <a class="dropdown-item logout pb-0" href="signin.html"><img
                    src="assets/img/icons/log-out.svg" class="me-2" alt="img">🚪 Déconnexion</a>
        </div>
    </div>
</li>

            </ul>



        </div>

        <div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="index.html"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
                            📊 Tableau de Bord</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
                            🍾 Produits</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="productlist.html">📦 Liste des Produits</a></li>
                        <li><a href="addproduct.html">➕ Ajouter un Produit</a></li>
                        <li><a href="categorylist.html">📂 Liste des Catégories</a></li>
                        <li><a href="addcategory.html">➕ Ajouter une Catégorie</a></li>
                        <li><a href="subcategorylist.html">📁 Liste des Sous-Catégories</a></li>
                        <li><a href="subaddcategory.html">➕ Ajouter une Sous-Catégorie</a></li>
                        <li><a href="brandlist.html">🏷️ Liste des Marques</a></li>
                        <li><a href="addbrand.html">➕ Ajouter une Marque</a></li>
                        <li><a href="importproduct.html">📥 Importer des Produits</a></li>
                        <li><a href="barcode.html">🏷️ Imprimer un Code-Barres</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img"><span>
                            🛒 Ventes</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="saleslist.html">📜 Liste des Ventes</a></li>
                        <li><a href="pos.html">💰 Point de Vente (POS)</a></li>
                        <li><a href="salesreturnlists.html">↩️ Retours de Vente</a></li>
                        <li><a href="createsalesreturns.html">➕ Nouveau Retour</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
                            🏪 Achats</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaselist.html">🛍️ Liste des Achats</a></li>
                        <li><a href="addpurchase.html">➕ Ajouter un Achat</a></li>
                        <li><a href="importpurchase.html">📥 Importer un Achat</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg" alt="img"><span>
                            💸 Dépenses</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="expenselist.html">📑 Liste des Dépenses</a></li>
                        <li><a href="createexpense.html">➕ Ajouter une Dépense</a></li>
                        <li><a href="expensecategory.html">📂 Catégories de Dépenses</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
                            👥 Clients & Fournisseurs</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="customerlist.html">👤 Liste des Clients</a></li>
                        <li><a href="addcustomer.html">➕ Ajouter un Client</a></li>
                        <li><a href="supplierlist.html">🚛 Liste des Fournisseurs</a></li>
                        <li><a href="addsupplier.html">➕ Ajouter un Fournisseur</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg" alt="img"><span>
                            🔄 Transferts</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="transferlist.html">📋 Liste des Transferts</a></li>
                        <li><a href="addtransfer.html">➕ Ajouter un Transfert</a></li>
                        <li><a href="importtransfer.html">📥 Importer un Transfert</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg" alt="img"><span>
                            ↩️ Retours</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="salesreturnlist.html">📋 Retours de Vente</a></li>
                        <li><a href="createsalesreturn.html">➕ Ajouter un Retour de Vente</a></li>
                        <li><a href="purchasereturnlist.html">📋 Retours d'Achat</a></li>
                        <li><a href="createpurchasereturn.html">➕ Ajouter un Retour d'Achat</a></li>
                    </ul>
                </li>
                <li>
                    <a href="components.html"><i data-feather="layers"></i><span> ⚙️ Paramètres</span> </a>
                </li>
                <li>
                    <a href="blankpage.html"><i data-feather="file"></i><span> 📄 Page Blanche</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> ❌ Pages d'Erreur
                        </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="error-404.html">🚫 Erreur 404</a></li>
                        <li><a href="error-500.html">⚠️ Erreur 500</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Admine</h4>
                        <h6>Modifier un admin</h6>
                    </div>
                </div>

                <form class="card" method="post" action="./editUser.php">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="nom" value="<?= $_SESSION['admin']['nom'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Adresse</label>
                                    <input type="text" name="adr" value="<?= $_SESSION['admin']['adr'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <div class="form-addons">
                                        <input type="text" name="email" value="<?= $_SESSION['admin']['email'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" name="prenom" value="<?= $_SESSION['admin']['prenom'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Téléphone</label>
                                    <input type="text" name="tele" value="<?= $_SESSION['admin']['tele'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Mot de passe</label>
                                    <div class="pass-group">
                                        <input type="password" class=" pass-inputs" name="tele"
                                            value="<?= $_SESSION['admin']['mdp'] ?>">
                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label> Profile Picture</label>
                                    <div class="image-upload image-upload-new">
                                        <input type="file">
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Drag and drop a file to upload</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <a href="javascript:void(0);" class="btn btn-submit me-2" type="submit"
                                    name="modifier">Modifier</a>
                                <a href="javascript:void(0);" class="btn btn-cancel" type="reset">Annuler</a>
                            </div>
                        </div>
                    </div>
                </form>
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