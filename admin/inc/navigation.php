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
          <a href="<?php echo base_url ?>admin/?page=receiving" class="nav-link nav-receiving">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>Réception des stocks</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=back_order" class="nav-link nav-back_order">
            <i class="nav-icon fas fa-clock"></i>
            <p>Commandes en attente</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=return" class="nav-link nav-return">
            <i class="nav-icon fas fa-undo-alt"></i>
            <p>Retours produits</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=choffeur" class="nav-link nav-chauffeur">
            <i class="nav-icon fas fa-id-badge"></i>
            <p>Chauffeurs</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=fournisseur" class="nav-link nav-fournisseur">
            <i class="nav-icon fas fa-handshake"></i>
            <p>Versement fournisseurs</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=jour" class="nav-link nav-versement_journalier">
            <i class="nav-icon fas fa-coins"></i>
            <p>Versement journalier</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=bordereau" class="nav-link nav-bordereau">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Bordereaux de livraison</p>
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
          <a href="<?php echo base_url ?>admin/?page=sales" class="nav-link nav-sales">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Ventes</p>
          </a>
        </li>

        <?php if($_settings->userdata('type') == 1): ?>
        <li class="nav-header">Administration</li>
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
      </ul>
    </nav>
  </div>
</aside>

<style>
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
  .nav-sidebar .nav-item a::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    bottom: 0;
    left: -100%;
    background: #f39c12;
    transition: all 0.3s ease-in-out;
  }
  .nav-sidebar .nav-item a:hover::after {
    left: 0;
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
