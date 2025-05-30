<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-primary text-sm">
    <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 1.8rem;height: 1.8rem;">
    <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-4">
      <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu">
        
        <!-- Si l'utilisateur est un livreur (type = 3) -->
        <?php if ($_settings->userdata('type') == 3): ?>
          <li class="nav-item">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>Tableau de bord</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=Livreurs" class="nav-link nav-back_order">
              <i class="nav-icon fas fa-clock"></i>
              <p>Vente par livraison</p>
            </a>
          </li>
        <?php endif; ?>

        <!-- Si l'utilisateur est un autre type (par exemple, administrateur ou personnel) -->
        <?php if ($_settings->userdata('type') != 3): ?>
          <li class="nav-item">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>Tableau de bord</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=purchase_order" class="nav-link nav-purchase_order">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Commandes fournisseurs</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=personnel" class="nav-link nav-personnel">
              <i class="nav-icon fas fa-users"></i>
              <p>Gestion du personnel</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=stocks" class="nav-link nav-stocks">
              <i class="nav-icon fas fa-box"></i>
              <p>Gestion des stocks</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=maintenance/supplier" class="nav-link nav-maintenance_supplier">
              <i class="nav-icon fas fa-truck-loading"></i>
              <p>Fournisseur</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=sales" class="nav-link nav-sales">
              <i class="nav-icon fas fa-receipt"></i>
              <p>Ventes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=clients" class="nav-link nav-clients">
              <i class="nav-icon fas fa-user"></i>
              <p>Clients</p>
            </a>
          </li>

          <?php if ($_settings->userdata('type') == 1): ?>
            <li class="nav-header">Administration</li>
            <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=Livreurs" class="nav-link nav-back_order">
              <i class="nav-icon fas fa-clock"></i>
              <p>Vente par livraison</p>
            </a>
          </li>

            <li class="nav-item">
              <a href="<?php echo base_url ?>admin/?page=maintenance/item" class="nav-link nav-maintenance_item">
                <i class="nav-icon fas fa-boxes"></i>
                <p>Liste des Articles</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
                <i class="nav-icon fas fa-users"></i>
                <p>Utilisateurs</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Paramètres</p>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</aside>

<style>
  /* Appliquer un scroll si trop long */
  .sidebar {
    max-height: 100vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #f39c12 #343a40;
  }

  /* Personnalisation de la barre de défilement */
  .sidebar::-webkit-scrollbar {
    width: 8px;
  }

  .sidebar::-webkit-scrollbar-track {
    background: #343a40;
  }

  .sidebar::-webkit-scrollbar-thumb {
    background-color: #f39c12;
    border-radius: 4px;
  }

  /* Effets visuels sur les liens */
  .nav-sidebar .nav-item a {
    transition: all 0.3s ease-in-out;
    position: relative;
  }

  .nav-sidebar .nav-item a:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.05);
  }

  .nav-sidebar .nav-item a.active {
    background: #f39c12 !important;
    color: #fff !important;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    gsap.from(".nav-sidebar .nav-item", {
      opacity: 0,
      y: 20,
      duration: 0.6,
      stagger: 0.1,
      ease: "power3.out"
    });

    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    page = page.replace(/\//g, '_');

    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active');

      if ($('.nav-link.nav-' + page).parents('.nav-item').hasClass('menu-open') == false) {
        $('.nav-link.nav-' + page).parents('.nav-item').addClass('menu-open');
      }
    }
  });
</script>
