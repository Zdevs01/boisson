<style>
  .user-img {
        position: absolute;
        height: 30px;
        width: 30px;
        object-fit: cover;
        left: -10%;
        top: -10%;
  }
  .btn-rounded {
        border-radius: 50px;
  }
  .navbar-lightblue {
        background: linear-gradient(45deg, #002f4b, #f7941d);
        color: white;
  }
  .user-status {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #28a745;
        bottom: 0;
        right: 0;
        border: 2px solid white;
  }
  .navbar-nav .nav-link {
        color: white;
        font-size: 14px;
  }
  .navbar-nav .nav-link:hover {
        color: #f7941d;
  }
  .dropdown-menu {
        padding: 0;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  }
  .dropdown-item {
        padding: 10px;
        color: #343a40;
  }
  .dropdown-item:hover {
        background-color: #f8f9fa;
  }
</style>

<!-- Barre de navigation -->
<nav class="main-header navbar navbar-expand navbar-dark border border-light border-top-0 border-left-0 border-right-0 navbar-light text-sm navbar-lightblue">
    <!-- Liens de gauche -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo base_url ?>" class="nav-link">
                <?php echo (!isMobileDevice()) ? $_settings->info('name') : $_settings->info('short_name'); ?> - Gestion Entrepôt
            </a>
        </li>
    </ul>
    
    <!-- Liens de droite -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <div class="btn-group nav-link">
                <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span>
                        <img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="Image Utilisateur">
                        <span class="user-status"></span> <!-- Indicateur de statut en ligne -->
                    </span>
                    <span class="ml-3"> <?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?> </span>
                    <span class="sr-only">Basculer le menu</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="<?php echo base_url.'admin/?page=user' ?>">
                        <span class="fa fa-user"></span> Mon Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>">
                        <span class="fas fa-sign-out-alt"></span> Déconnexion
                    </a>
                </div>
            </div>
        </li>
    </ul>
</nav>
<!-- Fin de la barre de navigation -->
