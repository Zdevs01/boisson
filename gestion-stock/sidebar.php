<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="index.php" class="<?= $active[0]; ?>">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span> Tableau de Bord </span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/quotation1.svg" alt="img">
                        <span> Catégories </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="categorylist.php" class="<?= $active[3]; ?>">Liste des Catégories</a></li>
                        <li><a href="addcategory.php" class="<?= $active[4]; ?>">Ajouter une Catégorie</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/scanners.svg" alt="img">
                        <span> Type de Produit </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="brandlist.php" class="<?= $active[1]; ?>">Type de Produit</a></li>
                        <li><a href="addbrand.php" class="<?= $active[2]; ?>">Ajouter un Type de Produit</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/product.svg" alt="img">
                        <span> Produits </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="productlist.php" class="<?= $active[5]; ?>">Liste des Produits</a></li>
                        <li><a href="addproduct.php" class="<?= $active[6]; ?>">Ajouter un Produit</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span> Clients </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="customerlist.php" class="<?= $active[11]; ?>">Liste des Clients</a></li>
                        <li><a href="addcustomer.php" class="<?= $active[12]; ?>">Ajouter un Client</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/sales1.svg" alt="img">
                        <span> Ventes </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="salesreturnlists.php" class="<?= $active[7]; ?>">Liste des Ventes</a></li>
                        <li><a href="createsalesreturns.php" class="<?= $active[8]; ?>">Nouvelle Vente</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span> Fournisseurs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="supplierlist.php" class="<?= $active[13]; ?>">Liste des Fournisseurs</a></li>
                        <li><a href="addsupplier.php" class="<?= $active[14]; ?>">Ajouter un Fournisseur</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/purchase1.svg" alt="img">
                        <span> Achats </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="purchaselist.php" class="<?= $active[9]; ?>">Liste des Achats</a></li>
                        <li><a href="addpurchase.php" class="<?= $active[10]; ?>">Ajouter un Achat</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span> Administration </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="newuser.php" class="<?= $active[15]; ?>">Nouvel Administrateur</a></li>
                        <li><a href="userlists.php" class="<?= $active[16]; ?>">Liste des Administrateurs</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
